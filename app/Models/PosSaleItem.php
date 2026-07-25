<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosSaleItem extends Model
{
    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($item) {
            if ($item->posSale) {
                $warehouseId = data_get($item->posSale, 'warehouse_id');
                $usedDefaultWarehouse = false;

                if (! $warehouseId && $item->posSale->branch_id) {
                    $warehouseId = Warehouse::defaultIdForBranch((int) $item->posSale->branch_id);
                    $usedDefaultWarehouse = (bool) $warehouseId;
                }

                $inventoryService = new \App\Services\InventoryService();
                $inventoryService->updateStock(
                    $item->product_id,
                    $item->product_variant_id,
                    $item->posSale->branch_id,
                    $warehouseId,
                    'out',
                    $item->quantity,
                    $item->unit_price,
                    $item->posSale->invoice_number ?? 'POS-' . $item->posSale->id,
                    'App\\Models\\PosSale',
                    $usedDefaultWarehouse
                );
            }
        });
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
