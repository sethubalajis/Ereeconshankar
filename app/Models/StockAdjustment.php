<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockAdjustment extends Model
{
    protected $guarded = [];

    protected static function booted()
    {
        static::saved(function ($model) {
            if ($model->isDirty('status') && $model->status === 'approved') {
                $inventoryService = new \App\Services\InventoryService();
                $type = $model->quantity >= 0 ? 'in' : 'out';
                $absQuantity = abs($model->quantity);
                
                $inventoryService->updateStock(
                    $model->product_id,
                    $model->product_variant_id,
                    $model->branch_id,
                    $model->warehouse_id,
                    $type,
                    $absQuantity,
                    0, // cost adjustment might require more complex logic, leave 0 for now
                    'ADJ-' . $model->id,
                    'App\\Models\\StockAdjustment'
                );
            }
        });
    }

    //
    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    public function warehouse() {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function productVariant() {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}
