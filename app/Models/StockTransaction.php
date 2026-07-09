<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockTransaction extends Model
{
    protected $guarded = [];

    //
    public function stock() {
        return $this->belongsTo(Stock::class, 'stock_id');
    }

    protected static function booted()
    {
        static::created(function ($model) {
            $stock = $model->stock;
            if ($stock) {
                $inward = 0;
                $outward = 0;

                // Adjust opening quantity depending on transaction type.
                // Assuming 'in' means quantity was added to stock, 'out' means subtracted.
                if ($model->transaction_type === 'in') {
                    $inward = abs($model->quantity);
                    $opening = $stock->quantity - $inward;
                } elseif ($model->transaction_type === 'out') {
                    $outward = abs($model->quantity);
                    $opening = $stock->quantity + $outward;
                } else {
                    // For any other types like 'adjustment', 'transfer'
                    // If quantity in transaction is positive it's inward, else outward
                    if ($model->quantity > 0) {
                        $inward = $model->quantity;
                        $opening = $stock->quantity - $inward;
                    } else {
                        $outward = abs($model->quantity);
                        $opening = $stock->quantity + $outward;
                    }
                }

                \App\Models\StockLedger::create([
                    'product_id' => $stock->product_id,
                    'product_variant_id' => $stock->product_variant_id,
                    'branch_id' => $stock->branch_id,
                    'warehouse_id' => $stock->warehouse_id,
                    'transaction_date' => now()->toDateString(),
                    'opening_qty' => $opening,
                    'inward_qty' => $inward,
                    'outward_qty' => $outward,
                    'closing_qty' => $stock->quantity,
                ]);
            }
        });
    }
}
