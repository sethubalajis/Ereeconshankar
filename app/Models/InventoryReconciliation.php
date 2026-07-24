<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryReconciliation extends Model
{
    protected $guarded = [];

    protected static function booted()
    {
        static::saving(function ($model) {
            $systemQty = (float) ($model->system_qty ?? 0);
            $physicalQty = (float) ($model->physical_qty ?? 0);

            $model->variance = $physicalQty - $systemQty;
        });
    }

    //
    public function inventoryCount() {
        return $this->belongsTo(InventoryCount::class, 'inventory_count_id');
    }
    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function productVariant() {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}
