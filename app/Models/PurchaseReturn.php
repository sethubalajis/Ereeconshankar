<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseReturn extends Model
{
    protected $guarded = [];

    protected $casts = [
        'returned_items' => 'array',
    ];

    protected static function booted()
    {
        static::created(function ($model) {
            if (is_array($model->returned_items) && count($model->returned_items) > 0) {
                foreach ($model->returned_items as $item) {
                    $productId = $item['product_id'] ?? null;
                    if (!$productId) continue;

                    $variantId = $item['product_variant_id'] ?? null;
                    $quantity = (float)($item['quantity'] ?? 0);

                    $warehouseId = $model->grn ? $model->grn->warehouse_id : null;

                    $stock = \App\Models\Stock::where([
                        'product_id' => $productId,
                        'product_variant_id' => $variantId,
                        'branch_id' => $model->branch_id,
                    ])->when($warehouseId, function ($query) use ($warehouseId) {
                        return $query->where('warehouse_id', $warehouseId);
                    })->first();

                    if ($stock) {
                        $stock->quantity -= $quantity;
                        $stock->save();

                        \App\Models\StockTransaction::create([
                            'stock_id' => $stock->id,
                            'transaction_type' => 'out',
                            'quantity' => $quantity,
                            'reference' => 'PR-' . $model->id,
                            'reference_type' => 'App\\Models\\PurchaseReturn',
                        ]);
                    }
                }
            }
        });
    }

    //
    public function purchaseOrder() {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }
    public function grn() {
        return $this->belongsTo(Grn::class, 'grn_id');
    }
    public function vendor() {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
