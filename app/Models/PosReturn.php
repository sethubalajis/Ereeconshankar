<?php

namespace App\Models;

use App\Traits\HasBranch;

use App\Traits\BelongsToFinancialYear;

use Illuminate\Database\Eloquent\Model;

class PosReturn extends Model
{
    use HasBranch;

    use BelongsToFinancialYear;
    protected $guarded = [];

    protected $casts = [
        'return_items' => 'array',
    ];

    protected static function booted(): void
    {
        static::saved(function (self $model): void {
            $shouldApplyStockNow = ($model->wasRecentlyCreated && in_array($model->status, ['completed', 'refunded'], true))
                || ($model->wasChanged('status') && in_array($model->status, ['completed', 'refunded'], true));

            if (! $shouldApplyStockNow) {
                return;
            }

            $sale = $model->posSale;

            if (! $sale || ! $sale->branch_id) {
                return;
            }

            $warehouseId = data_get($sale, 'warehouse_id');
            $usedDefaultWarehouse = false;

            if (! $warehouseId) {
                $warehouseId = Warehouse::defaultIdForBranch((int) $sale->branch_id);
                $usedDefaultWarehouse = (bool) $warehouseId;
            }

            $inventoryService = new \App\Services\InventoryService();
            $saleItems = $sale->posSaleItems()->orderBy('id')->get()->values();

            foreach (($model->return_items ?? []) as $index => $item) {
                $fallbackSaleItemId = (int) ($saleItems->get($index)?->id ?? 0);
                $posSaleItemId = (int) ($item['pos_sale_item_id'] ?? $fallbackSaleItemId);
                $returnQty = (float) ($item['return_quantity'] ?? 0);

                if ($posSaleItemId <= 0 || $returnQty <= 0) {
                    continue;
                }

                $condition = $item['condition'] ?? 'Good';
                
                if ($condition === 'Damaged') {
                    $damagedWarehouse = Warehouse::getDamagedWarehouseForBranch((int) $sale->branch_id);
                    $targetWarehouseId = $damagedWarehouse ? $damagedWarehouse->id : $warehouseId;
                    $reference = 'POS-RET-DAMAGED-' . $model->id . '-' . $posSaleItemId;
                    $isDamaged = true;
                } else {
                    $targetWarehouseId = $warehouseId;
                    $reference = 'POS-RET-SALEABLE-' . $model->id . '-' . $posSaleItemId;
                    $isDamaged = false;
                }

                $alreadyApplied = StockTransaction::query()
                    ->where('reference', $reference)
                    ->where('reference_type', 'App\\Models\\PosReturn')
                    ->exists();

                if ($alreadyApplied) {
                    continue;
                }

                $saleItem = PosSaleItem::query()->find($posSaleItemId)
                    ?? $saleItems->firstWhere('id', $posSaleItemId);

                if (! $saleItem) {
                    continue;
                }

                $unitPrice = (float) ($item['unit_price'] ?? $saleItem->unit_price ?? 0);

                if (! $isDamaged) {
                    $inventoryService->updateStock(
                        $saleItem->product_id,
                        $saleItem->product_variant_id,
                        $sale->branch_id,
                        $targetWarehouseId,
                        'in',
                        $returnQty,
                        $unitPrice,
                        $reference,
                        'App\\Models\\PosReturn',
                        false,
                        $usedDefaultWarehouse && !$isDamaged
                    );
                } else {
                    \App\Models\DamagedProduct::create([
                        'product_id' => $saleItem->product_id,
                        'product_variant_id' => $saleItem->product_variant_id,
                        'branch_id' => $sale->branch_id,
                        'warehouse_id' => $targetWarehouseId,
                        'quantity' => $returnQty,
                        'source_type' => 'App\\Models\\PosReturn',
                        'source_id' => $model->id,
                        'reason' => $item['return_reason'] ?? 'Damaged Return',
                        'reported_date' => $model->return_date ?? now(),
                        'status' => 'Quarantined',
                    ]);
                }
            }
            
            // Process Wallet Refund
            if ($sale->customer_id) {
                $walletRef = 'Refunded for POS Return #' . $model->id;
                $alreadyCredited = \App\Models\CustomerWallet::where('pos_sale_id', $sale->id)
                    ->where('transaction_type', 'credit')
                    ->where('reference', $walletRef)
                    ->exists();

                if (!$alreadyCredited && $model->return_amount > 0) {
                    $customer = \App\Models\Customer::find($sale->customer_id);
                    if ($customer) {
                        $walletService = app(\App\Services\WalletService::class);
                        $walletService->creditWallet($customer, $sale, (float) $model->return_amount, $walletRef);
                    }
                }
            }

            // Accounting & GST Reversal & Credit Note
            if ($model->return_amount > 0) {
                $accountingService = app(\App\Services\AccountingService::class);
                $gstService = app(\App\Services\GstService::class);

                // Assuming 18% average GST just for this implementation
                $taxableAmount = round($model->return_amount / 1.18, 2);
                $gstAmount = $model->return_amount - $taxableAmount;
                $gstData = $gstService->calculateGst((float)$taxableAmount, 18.0);
                
                // Create Credit Note
                if ($sale->customer_id) {
                    $creditNote = \App\Models\CreditNote::firstOrCreate(
                        ['note_number' => 'CN-POS-' . $model->id],
                        [
                            'customer_id' => $sale->customer_id,
                            'note_date' => now(),
                            'amount' => $model->return_amount,
                            'reason' => 'POS Return',
                            'branch_id' => $sale->branch_id,
                            'company_id' => clone $sale->company_id ?? null,
                            'financial_year_id' => clone $model->financial_year_id ?? null,
                        ]
                    );
                }

                // Accounting Entry (Reverse Sales, Reverse Output GST, Decrease Cash/Customer)
                $salesAcc = \App\Models\ChartOfAccount::where('account_code', '4001')->first();
                $cashAcc = \App\Models\ChartOfAccount::where('account_code', '1001')->first();
                $gstOutAcc = \App\Models\ChartOfAccount::where('account_code', '2001')->first();

                if ($salesAcc && $cashAcc && $gstOutAcc) {
                    // check if already posted
                    $existingJournal = \App\Models\Journal::where('journal_number', 'JRN-RET-' . $model->id)->first();
                    if (!$existingJournal) {
                        $accountingService->postJournalEntry([
                            'journal_number' => 'JRN-RET-' . $model->id,
                            'journal_date' => now(),
                            'reference' => 'POS Return ' . $model->id,
                            'branch_id' => clone $sale->branch_id ?? null,
                            'company_id' => clone $sale->company_id ?? null,
                            'financial_year_id' => clone $model->financial_year_id ?? null,
                            'total_debit' => clone $model->return_amount ?? 0,
                            'total_credit' => clone $model->return_amount ?? 0,
                        ], [
                            [
                                'chart_of_account_id' => $salesAcc->id,
                                'debit' => clone $taxableAmount,
                                'credit' => 0,
                                'branch_id' => clone $sale->branch_id ?? null,
                                'company_id' => clone $sale->company_id ?? null,
                                'financial_year_id' => clone $model->financial_year_id ?? null,
                            ],
                            [
                                'chart_of_account_id' => $gstOutAcc->id,
                                'debit' => clone $gstAmount,
                                'credit' => 0,
                                'branch_id' => clone $sale->branch_id ?? null,
                                'company_id' => clone $sale->company_id ?? null,
                                'financial_year_id' => clone $model->financial_year_id ?? null,
                            ],
                            [
                                'chart_of_account_id' => $cashAcc->id,
                                'debit' => 0,
                                'credit' => clone $model->return_amount ?? 0,
                                'branch_id' => clone $sale->branch_id ?? null,
                                'company_id' => clone $sale->company_id ?? null,
                                'financial_year_id' => clone $model->financial_year_id ?? null,
                            ]
                        ]);
                    }
                }
            }
        });
    }

    //
    public function posSale() {
        return $this->belongsTo(PosSale::class, 'pos_sale_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
