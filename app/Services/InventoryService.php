<?php

namespace App\Services;

use App\Models\Stock;
use App\Models\StockTransaction;
use App\Models\StockLedger;
use App\Models\StockValuation;

class InventoryService
{
    /**
     * Update stock for a given business transaction.
     * 
     * @param int $productId
     * @param int|null $variantId
     * @param int $branchId
     * @param int|null $warehouseId
     * @param string $transactionType 'in' or 'out'
     * @param float $quantity
     * @param float $unitPrice
     * @param string $reference
     * @param string $referenceType
    * @param bool $preventNewStockOnOut When true and transaction is out, do not insert a new stock row.
    * @param bool $reuseExistingStockRow When true, reuse an existing branch stock row instead of creating a new one.
     * @return \App\Models\Stock|null
     */
    public function updateStock($productId, $variantId, $branchId, $warehouseId, $transactionType, $quantity, $unitPrice, $reference, $referenceType, $preventNewStockOnOut = false, $reuseExistingStockRow = false)
    {
        $stock = Stock::query()->where([
            'product_id' => $productId,
            'product_variant_id' => $variantId,
            'branch_id' => $branchId,
        ])->first();

        if (! $stock) {
            if (($transactionType === 'out' && $preventNewStockOnOut) || $reuseExistingStockRow) {
                // Reuse an existing branch-level stock row instead of creating a new one.
                $stock = Stock::query()
                    ->where('product_id', $productId)
                    ->where('product_variant_id', $variantId)
                    ->where('branch_id', $branchId)
                    ->orderByDesc('quantity')
                    ->first();

                if (! $stock) {
                    return null;
                }
            } else {
                $stock = new Stock([
                    'product_id' => $productId,
                    'product_variant_id' => $variantId,
                    'branch_id' => $branchId,
                ]);
            }
        }

        $openingQty = $stock->exists ? $stock->quantity : 0;
        
        // 1. Update Stock
        if ($transactionType === 'in') {
            $totalCurrentValue = $stock->quantity * ($stock->avg_cost ?? 0);
            $newReceivedValue = $quantity * $unitPrice;
            $newTotalQuantity = $stock->quantity + $quantity;
            
            if ($newTotalQuantity > 0) {
                $stock->avg_cost = ($totalCurrentValue + $newReceivedValue) / $newTotalQuantity;
            }
            $stock->quantity += $quantity;
        } else {
            // 'out'
            $stock->quantity -= $quantity;
        }
        
        $stock->available_qty = $stock->quantity - ($stock->reserved_qty ?? 0) - ($stock->damaged_qty ?? 0);
        $stock->save();

        // 2. Create Stock Transaction
        StockTransaction::create([
            'stock_id' => $stock->id,
            'company_id' => $stock->branch?->company_id ?? null,
            'branch_id' => $branchId,
            'warehouse_id' => $stock->warehouse_id,
            'product_id' => $productId,
            'transaction_type' => $transactionType,
            'quantity' => $quantity,
            'on_hand_before' => $openingQty,
            'on_hand_after' => $stock->quantity,
            'reserved_before' => $stock->exists ? $stock->getOriginal('reserved_qty') ?? 0 : 0,
            'reserved_after' => $stock->reserved_qty ?? 0,
            'available_before' => $stock->exists ? $stock->getOriginal('available_qty') ?? 0 : 0,
            'available_after' => $stock->available_qty,
            'user_id' => auth()->id(),
            'transaction_date' => now(),
            'reference' => $reference,
            'reference_type' => $referenceType,
        ]);

        // 3. Create Stock Ledger for this exact transaction
        $date = now()->format('Y-m-d');
        $ledger = new StockLedger([
            'product_id' => $productId,
            'product_variant_id' => $variantId,
            'branch_id' => $branchId,
            'warehouse_id' => $stock->warehouse_id,
            'transaction_date' => $date,
            'opening_qty' => $openingQty,
            'inward_qty' => $transactionType === 'in' ? $quantity : 0,
            'outward_qty' => $transactionType === 'out' ? $quantity : 0,
            'closing_qty' => $stock->quantity,
        ]);
        $ledger->save();

        // 4. Update Stock Valuation
        $valuation = StockValuation::firstOrNew([
            'branch_id' => $branchId,
            'valuation_date' => $date,
        ]);

        // total value across all stocks in the branch
        $totalValue = Stock::where('branch_id', $branchId)->get()->sum(function($s) {
            return $s->quantity * ($s->avg_cost ?? 0);
        });

        $valuation->total_value = $totalValue;
        $valuation->save();
        
        return $stock;
    }
}
