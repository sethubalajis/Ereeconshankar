<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosSaleItem extends Model
{
    protected $guarded = [];

    protected static function booted()
    {
        static::saved(function ($item) {
            $item->processStockDeduction();
        });
        
        static::deleted(function ($item) {
            $item->processStockRestore();
        });
    }

    public function processStockDeduction(): void
    {
        if (! $this->posSale || $this->posSale->status !== 'completed') {
            return;
        }

        $reference = 'POS-SALE-' . $this->pos_sale_id . '-' . $this->id;

        $existingOutTransactions = StockTransaction::query()
            ->where('reference', 'like', $reference . '%')
            ->whereNot('reference', 'like', 'POS-SALE-RESTORE-%')
            ->where('reference_type', 'App\\Models\\PosSale')
            ->get();
            
        $netDeducted = 0;
        foreach ($existingOutTransactions as $tx) {
            if ($tx->transaction_type === 'out') {
                $netDeducted += $tx->quantity;
            } else {
                $netDeducted -= $tx->quantity;
            }
        }

        $diff = $this->quantity - $netDeducted;
        
        if ($diff == 0) {
            return;
        }

        $warehouseId = data_get($this->posSale, 'warehouse_id');
        $usedDefaultWarehouse = false;

        if (! $warehouseId && $this->posSale->branch_id) {
            $warehouseId = Warehouse::defaultIdForBranch((int) $this->posSale->branch_id);
            $usedDefaultWarehouse = (bool) $warehouseId;
        }

        $inventoryService = new \App\Services\InventoryService();
        
        $transactionType = $diff > 0 ? 'out' : 'in';
        $qtyToAdjust = abs($diff);
        
        $adjReference = $existingOutTransactions->isEmpty() ? $reference : $reference . '-ADJ-' . uniqid();

        $inventoryService->updateStock(
            $this->product_id,
            $this->product_variant_id,
            $this->posSale->branch_id,
            $warehouseId,
            $transactionType,
            $qtyToAdjust,
            $this->unit_price,
            $adjReference,
            'App\\Models\\PosSale',
            true, // preventNewStockOnOut
            true  // reuseExistingStockRow
        );
    }
    
    public function processStockRestore(): void
    {
        if (! $this->posSale || $this->posSale->status !== 'completed') {
            return;
        }

        $reference = 'POS-SALE-' . $this->pos_sale_id . '-' . $this->id;
        $restoreRef = 'POS-SALE-RESTORE-' . $this->pos_sale_id . '-' . $this->id;

        $previouslyDeducted = StockTransaction::query()
            ->where('reference', $reference)
            ->where('reference_type', 'App\\Models\\PosSale')
            ->exists();

        $alreadyRestored = StockTransaction::query()
            ->where('reference', $restoreRef)
            ->where('reference_type', 'App\\Models\\PosSale')
            ->exists();

        if ($previouslyDeducted && ! $alreadyRestored) {
            $warehouseId = data_get($this->posSale, 'warehouse_id');
            if (! $warehouseId && $this->posSale->branch_id) {
                $warehouseId = Warehouse::defaultIdForBranch((int) $this->posSale->branch_id);
            }

            $inventoryService = new \App\Services\InventoryService();
            $inventoryService->updateStock(
                $this->product_id,
                $this->product_variant_id,
                $this->posSale->branch_id,
                $warehouseId,
                'in',
                $this->quantity,
                $this->unit_price,
                $restoreRef,
                'App\\Models\\PosSale',
                false,
                true
            );
        }
    }

    //
    public function posSale() {
        return $this->belongsTo(PosSale::class, 'pos_sale_id');
    }
    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function productVariant() {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
    public function appliedPriceList() {
        return $this->belongsTo(PriceList::class, 'price_list_id');
    }
}
