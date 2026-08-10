<?php

namespace App\Observers;

use App\Models\PosSale;

class PosSaleObserver
{
    /**
     * Handle the PosSale "created" event.
     */
    public function created(PosSale $model): void
    {
        if ($model->customer_id) {
            $customer = \App\Models\Customer::find($model->customer_id);
            if ($customer) {
                // Find Active Loyalty Program
                $program = \App\Models\LoyaltyProgram::resolveForCustomer($customer);

                // Deduct redeemed points
                if ($model->redeem_points > 0) {
                    $pointsToDeduct = (int) $model->redeem_points;
                    $loyaltyService = app(\App\Services\LoyaltyService::class);
                    try {
                        $loyaltyService->redeemPoints($customer, $model, $pointsToDeduct);
                        $customer->lifetime_points_redeemed += $pointsToDeduct;
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::error("Loyalty redemption failed: " . $e->getMessage());
                    }
                }
                
                // Earn new points
                if ($program && $model->net_amount > 0) {
                    $loyaltyService = app(\App\Services\LoyaltyService::class);
                    $amountPerPoint = max(1, $program->amount_per_point ?? 100); // Fallback to 100 if missing
                    $pointsEarned = (int) floor($model->net_amount / $amountPerPoint) * ($program->points_per_amount ?? 1);
                    if ($pointsEarned > 0) {
                        try {
                            $loyaltyService->earnPoints($customer, $model, $pointsEarned);
                            $customer->lifetime_points_earned += $pointsEarned;
                        } catch (\Exception $e) {
                            \Illuminate\Support\Facades\Log::error("Loyalty earning failed: " . $e->getMessage());
                        }
                    }
                }


                // Deduct used wallet balance
                if ($model->use_wallet_balance > 0) {
                    $customer->wallet_balance -= $model->use_wallet_balance;
                    \Illuminate\Support\Facades\DB::table('customer_wallets')->insert([
                        'customer_id' => $customer->id,
                        'transaction_type' => 'debit',
                        'amount' => $model->use_wallet_balance,
                        'description' => 'Used on POS Sale ' . $model->invoice_number,
                        'transaction_date' => now()->toDateString(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                // Update CRM Metrics
                $customer->last_purchase_date = now()->toDateString();
                $customer->total_purchase_amount += $model->net_amount;
                $customer->total_invoices += 1;
                $customer->lifetime_purchase_value += $model->net_amount;
                $customer->average_purchase_value = $customer->total_invoices > 0 ? $customer->total_purchase_amount / $customer->total_invoices : 0;
                $customer->last_visited_branch_id = $model->branch_id;
                
                // Membership Plan Upgrade Logic
                $eligiblePlan = \App\Models\MembershipPlan::whereNotNull('upgrade_threshold')
                    ->where('status', true)
                    ->where('upgrade_threshold', '<=', $customer->lifetime_purchase_value)
                    ->orderBy('upgrade_threshold', 'desc')
                    ->first();

                if ($eligiblePlan && $customer->membership_plan_id !== $eligiblePlan->id) {
                    $customer->membership_plan_id = $eligiblePlan->id;
                    
                    // Expire old memberships
                    \App\Models\CustomerMembership::where('customer_id', $customer->id)
                        ->where('status', 'Active')
                        ->update(['status' => 'Expired', 'end_date' => now()->toDateString()]);
                    
                    // Assign new CustomerMembership
                    $durationMonths = $eligiblePlan->duration_months ?? 12;
                    \App\Models\CustomerMembership::create([
                        'customer_id' => $customer->id,
                        'membership_plan_id' => $eligiblePlan->id,
                        'start_date' => now()->toDateString(),
                        'end_date' => now()->addMonths($durationMonths)->toDateString(),
                        'status' => 'Active',
                    ]);
                }
                
                // Save customer profile automatically
                $customer->save();
            }
        }
        
        // Gift Voucher Deduction
        if ($model->gift_voucher_id && $model->gift_voucher_amount > 0) {
            $voucher = \App\Models\GiftVoucher::find($model->gift_voucher_id);
            if ($voucher && $voucher->amount >= $model->gift_voucher_amount) {
                $voucher->amount -= $model->gift_voucher_amount;
                if ($voucher->amount <= 0) {
                    $voucher->is_redeemed = true;
                }
                $voucher->save();
            }
        }

        // Increment Coupon Usage
        if (is_array($model->applied_offers)) {
            foreach ($model->applied_offers as $offer) {
                if (isset($offer['key']) && str_starts_with($offer['key'], 'coupon_')) {
                    $couponId = (int) str_replace('coupon_', '', $offer['key']);
                    if ($couponId) {
                        \App\Models\Coupon::where('id', $couponId)->increment('current_usage');
                    }
                }
            }
        }
        
        // Trigger Email Notification if completed
        if ($model->status === 'completed' && $model->customer_id) {
            $customer = $model->customer;
            if ($customer && $customer->email) {
                try {
                    \Illuminate\Support\Facades\Mail::to($customer->email)
                        ->send(new \App\Mail\PosInvoiceMail($model));
                    $model->updateQuietly(['email_status' => 'sent']);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error("Failed to send POS sale email: " . $e->getMessage());
                    $model->updateQuietly(['email_status' => 'failed']);
                }
            }
        }
    }

    /**
     * Handle the PosSale "updated" event.
     */
    public function updated(PosSale $posSale): void
    {
        if ($posSale->wasChanged('status')) {
            $newStatus = $posSale->status;
            $oldStatus = $posSale->getOriginal('status');

            if ($newStatus === 'completed' && $oldStatus !== 'completed') {
                foreach ($posSale->posSaleItems as $item) {
                    $item->processStockDeduction();
                }
                
                // Mark associated stock reservation as completed
                if ($posSale->stock_reservation_id) {
                    $reservation = \App\Models\StockReservation::find($posSale->stock_reservation_id);
                    if ($reservation && $reservation->status !== \App\Models\StockReservation::STATUS_COMPLETED) {
                        $reservation->update(['status' => \App\Models\StockReservation::STATUS_COMPLETED]);
                    }
                }
                
                // Trigger Email Notification
                if ($posSale->customer_id) {
                    $customer = $posSale->customer;
                    if ($customer && $customer->email) {
                        try {
                            \Illuminate\Support\Facades\Mail::to($customer->email)
                                ->send(new \App\Mail\PosInvoiceMail($posSale));
                            $posSale->updateQuietly(['email_status' => 'sent']);
                        } catch (\Exception $e) {
                            \Illuminate\Support\Facades\Log::error("Failed to send POS sale email: " . $e->getMessage());
                            $posSale->updateQuietly(['email_status' => 'failed']);
                        }
                    }
                }
            } elseif (in_array($newStatus, ['cancelled', 'refunded'], true) && $oldStatus === 'completed') {
                foreach ($posSale->posSaleItems as $item) {
                    $deductRef = 'POS-SALE-' . $posSale->id . '-' . $item->id;
                    $restoreRef = 'POS-SALE-RESTORE-' . $posSale->id . '-' . $item->id;

                    $previouslyDeducted = \App\Models\StockTransaction::query()
                        ->where('reference', $deductRef)
                        ->where('reference_type', 'App\\Models\\PosSale')
                        ->exists();

                    $alreadyRestored = \App\Models\StockTransaction::query()
                        ->where('reference', $restoreRef)
                        ->where('reference_type', 'App\\Models\\PosSale')
                        ->exists();

                    if ($previouslyDeducted && ! $alreadyRestored) {
                        $warehouseId = data_get($posSale, 'warehouse_id');
                        $usedDefaultWarehouse = false;

                        if (! $warehouseId && $posSale->branch_id) {
                            $warehouseId = \App\Models\Warehouse::defaultIdForBranch((int) $posSale->branch_id);
                            $usedDefaultWarehouse = (bool) $warehouseId;
                        }

                        $inventoryService = new \App\Services\InventoryService();
                        $inventoryService->updateStock(
                            $item->product_id,
                            $item->product_variant_id,
                            $posSale->branch_id,
                            $warehouseId,
                            'in',
                            $item->quantity,
                            $item->unit_price,
                            $restoreRef,
                            'App\\Models\\PosSale',
                            false, // preventNewStockOnOut
                            true // reuseExistingStockRow
                        );
                    }
                }
            }
        }
    }

    /**
     * Handle the PosSale "deleted" event.
     */
    public function deleted(PosSale $posSale): void
    {
        //
    }

    /**
     * Handle the PosSale "restored" event.
     */
    public function restored(PosSale $posSale): void
    {
        //
    }

    /**
     * Handle the PosSale "force deleted" event.
     */
    public function forceDeleted(PosSale $posSale): void
    {
        //
    }
}
