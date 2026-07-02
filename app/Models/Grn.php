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
                foreach ($model->received_items as $item) {
                    $productId = $item['product_id'] ?? null;
                    if (!$productId) continue;

                    $variantId = $item['product_variant_id'] ?? null;
                    $quantity = (float)($item['quantity'] ?? 0);
                    $unitPrice = (float)($item['unit_price'] ?? 0);

                    $stock = \App\Models\Stock::firstOrNew([
                        'product_id' => $productId,
                        'product_variant_id' => $variantId,
                        'branch_id' => $model->branch_id,
                    ]);

                    if ($stock->exists) {
                        $totalCurrentValue = $stock->quantity * $stock->avg_cost;
                        $newReceivedValue = $quantity * $unitPrice;
                        $newTotalQuantity = $stock->quantity + $quantity;
                        
                        if ($newTotalQuantity > 0) {
                            $stock->avg_cost = ($totalCurrentValue + $newReceivedValue) / $newTotalQuantity;
                        }
                        $stock->quantity += $quantity;
                    } else {
                        $stock->quantity = $quantity;
                        $stock->avg_cost = $unitPrice;
                    }
                    
                    $stock->save();
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
}
