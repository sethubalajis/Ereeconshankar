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
                $inventoryService = new \App\Services\InventoryService();
                $inventoryService->updateStock(
                    $item->product_id,
                    $item->product_variant_id,
                    $item->posSale->branch_id,
                    // If PosSale doesn't have a warehouse_id, we can check if it exists or use default. PosSales might not be bound to warehouse, wait... I should check if posSale has warehouse_id. If not, null.
                    $item->posSale->warehouse_id ?? null,
                    'out',
                    $item->quantity,
                    $item->unit_price,
                    $item->posSale->invoice_number ?? 'POS-' . $item->posSale->id,
                    'App\\Models\\PosSale'
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
