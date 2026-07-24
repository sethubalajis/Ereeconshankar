<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockAdjustment extends Model
{
    protected $guarded = [];

    protected static function booted()
    {
        static::saved(function ($model) {
            $approvedNow = ($model->wasRecentlyCreated && $model->status === 'approved')
                || ($model->wasChanged('status') && $model->status === 'approved');

            if ($approvedNow) {
                $reference = 'ADJ-' . $model->id;

                $alreadyApplied = StockTransaction::query()
                    ->where('reference', $reference)
                    ->where('reference_type', 'App\\Models\\StockAdjustment')
                    ->exists();

                if ($alreadyApplied) {
                    return;
                }

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
                    $reference,
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
