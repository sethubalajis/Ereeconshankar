<?php

namespace App\Models;

use App\Traits\HasBranch;

use App\Traits\BelongsToFinancialYear;

use Illuminate\Database\Eloquent\Model;

class PurchaseReturn extends Model
{
    use HasBranch;

    use BelongsToFinancialYear;
    protected $guarded = [];

    protected $casts = [
        'returned_items' => 'array',
    ];

    protected static function booted()
    {
        static::created(function ($model) {
            if (is_array($model->returned_items) && count($model->returned_items) > 0) {
                $inventoryService = new \App\Services\InventoryService();
                
                foreach ($model->returned_items as $item) {
                    $productId = $item['product_id'] ?? null;
                    if (!$productId) continue;

                    $variantId = $item['product_variant_id'] ?? null;
                    $quantity = (float)($item['quantity'] ?? 0);
                    $unitPrice = (float)($item['unit_price'] ?? 0); // Might not be present, but fallback to 0

                    $warehouseId = $model->grn ? $model->grn->warehouse_id : null;

                    $inventoryService->updateStock(
                        $productId,
                        $variantId,
                        $model->branch_id,
                        $warehouseId,
                        'out',
                        $quantity,
                        $unitPrice,
                        'PR-' . $model->id,
                        'App\\Models\\PurchaseReturn'
                    );
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
