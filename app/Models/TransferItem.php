<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferItem extends Model
{
    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($item) {
            if ($item->stockTransfer) {
                $inventoryService = new \App\Services\InventoryService();
                
                // Reduce from source branch
                $inventoryService->updateStock(
                    $item->product_id,
                    $item->product_variant_id,
                    $item->stockTransfer->from_branch_id,
                    null, // assuming no warehouse tracking for simple transfers for now
                    'out',
                    $item->quantity,
                    0,
                    'TRF-' . $item->stockTransfer->id,
                    'App\\Models\\StockTransfer'
                );
                
                // Increase to destination branch
                $inventoryService->updateStock(
                    $item->product_id,
                    $item->product_variant_id,
                    $item->stockTransfer->to_branch_id,
                    null,
                    'in',
                    $item->quantity,
                    0, // Cost could be transferred, but we will leave 0 or fetch avg_cost
                    'TRF-' . $item->stockTransfer->id,
                    'App\\Models\\StockTransfer'
                );
            }
        });
    }

    //
    public function stockTransfer() {
        return $this->belongsTo(StockTransfer::class, 'stock_transfer_id');
    }
    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function productVariant() {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}
