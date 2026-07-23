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
                    $openingBalance = $customer->total_points;
                    $customer->total_points -= $pointsToDeduct;
                    $customer->lifetime_points_redeemed += $pointsToDeduct;
                    \Illuminate\Support\Facades\DB::table('loyalty_points')->insert([
                        'customer_id' => $customer->id,
                        'pos_sale_id' => $model->id,
                        'loyalty_program_id' => $program ? $program->id : null,
                        'transaction_type' => 'redeem',
                        'points_redeemed' => $pointsToDeduct,
                        'opening_balance' => $openingBalance,
                        'closing_balance' => $customer->total_points,
                        'invoice_number' => $model->invoice_number,
                        'remarks' => 'Redeemed on POS Sale ' . $model->invoice_number,
                        'transaction_date' => now()->toDateString(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
                
                // Earn new points
                if ($program && $model->net_amount > 0) {
                    $amountPerPoint = max(1, $program->amount_per_point); // Prevent division by zero
                    $pointsEarned = floor($model->net_amount / $amountPerPoint) * $program->points_per_amount;
                    if ($pointsEarned > 0) {
                        $openingBalance = $customer->total_points;
                        $customer->total_points += $pointsEarned;
                        $customer->lifetime_points_earned += $pointsEarned;
                        \Illuminate\Support\Facades\DB::table('loyalty_points')->insert([
                            'customer_id' => $customer->id,
                            'pos_sale_id' => $model->id,
                            'loyalty_program_id' => $program->id,
                            'transaction_type' => 'earn',
                            'points_earned' => $pointsEarned,
                            'opening_balance' => $openingBalance,
                            'closing_balance' => $customer->total_points,
                            'invoice_number' => $model->invoice_number,
                            'remarks' => 'Earned from POS Sale ' . $model->invoice_number,
                            'transaction_date' => now()->toDateString(),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
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
    }

    /**
     * Handle the PosSale "updated" event.
     */
    public function updated(PosSale $posSale): void
    {
        //
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
