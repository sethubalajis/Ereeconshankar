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
    }

    public function processStockDeduction(): void
    {
        if (! $this->posSale || $this->posSale->status !== 'completed') {
            return;
        }

        $reference = 'POS-SALE-' . $this->pos_sale_id . '-' . $this->id;

        $alreadyApplied = StockTransaction::query()
            ->where('reference', $reference)
            ->where('reference_type', 'App\\Models\\PosSale')
            ->exists();

        if ($alreadyApplied) {
            return;
        }

        $warehouseId = data_get($this->posSale, 'warehouse_id');
        $usedDefaultWarehouse = false;

        if (! $warehouseId && $this->posSale->branch_id) {
            $warehouseId = Warehouse::defaultIdForBranch((int) $this->posSale->branch_id);
            $usedDefaultWarehouse = (bool) $warehouseId;
        }

        $inventoryService = new \App\Services\InventoryService();
        $inventoryService->updateStock(
            $this->product_id,
            $this->product_variant_id,
            $this->posSale->branch_id,
            $warehouseId,
            'out',
            $this->quantity,
            $this->unit_price,
            $reference,
            'App\\Models\\PosSale',
            true, // preventNewStockOnOut
            true  // reuseExistingStockRow
        );
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
}
