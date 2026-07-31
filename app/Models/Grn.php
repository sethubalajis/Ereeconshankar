<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grn extends Model
{
    protected $guarded = [];

    protected $casts = [
        'received_items' => 'array',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->grn_number)) {
                $lastGrn = self::orderBy('id', 'desc')->first();
                $nextId = $lastGrn ? $lastGrn->id + 1 : 1;
                $model->grn_number = 'GRN-' . date('Ymd') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            }
        });

        static::created(function ($model) {
            if (is_array($model->received_items) && count($model->received_items) > 0) {
                $inventoryService = new \App\Services\InventoryService();
                
                foreach ($model->received_items as $item) {
                    $productId = $item['product_id'] ?? null;
                    if (!$productId) continue;
                    
                    $product = \App\Models\Product::find($productId);
                    if (!$product) continue;

                    $variantId = $item['product_variant_id'] ?? null;
                    $quantity = (float)($item['quantity'] ?? 0);
                    $unitPrice = (float)($item['unit_price'] ?? 0);

                    if ($product->product_type === 'Asset') {
                        $assetCategory = \App\Models\AssetCategory::firstOrCreate(
                            ['name' => 'Uncategorized Hardware'],
                            ['code' => 'UNCAT-HW']
                        );

                        for ($i = 0; $i < $quantity; $i++) {
                            \App\Models\Asset::create([
                                'asset_category_id' => $assetCategory->id,
                                'branch_id' => $model->branch_id,
                                'name' => $product->name,
                                'asset_code' => 'AST-' . date('Ymd') . '-' . $product->id . '-' . uniqid(),
                                'purchase_date' => $model->created_at,
                                'purchase_cost' => $unitPrice,
                                'status' => 'active',
                            ]);
                        }
                    } else {
                        $inventoryService->updateStock(
                            $productId,
                            $variantId,
                            $model->branch_id,
                            $model->warehouse_id,
                            'in',
                            $quantity,
                            $unitPrice,
                            $model->grn_number ?? 'GRN-' . $model->id,
                            'App\\Models\\Grn'
                        );
                    }
                }
            }
        });
    }

    //
    public function purchaseOrder() {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }
    public function vendor() {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    public function warehouse() {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
}
