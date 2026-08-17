<?php

namespace App\Models;

use App\Traits\HasBranch;

use App\Traits\BelongsToFinancialYear;

use Illuminate\Database\Eloquent\Model;

class InventoryCount extends Model
{
    use HasBranch;

    use BelongsToFinancialYear;
    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->count_code)) {
                $last = self::query()->orderByDesc('id')->first();
                $nextId = $last ? ($last->id + 1) : 1;
                $model->count_code = 'IC-' . date('Ymd') . '-' . str_pad((string) $nextId, 4, '0', STR_PAD_LEFT);
            }
        });

        static::saved(function ($model) {
            $isActivated = ($model->wasRecentlyCreated && $model->status === 'active')
                || ($model->wasChanged('status') && $model->status === 'active');

            if ($isActivated) {
                $model->generateReconciliationsFromStock();
            }

            if ($model->wasChanged('status') && $model->status === 'approved') {
                foreach ($model->inventoryReconciliations as $reconciliation) {
                    if ($reconciliation->variance != 0) {
                        \App\Models\StockAdjustment::create([
                            'branch_id' => $model->branch_id,
                            'product_id' => $reconciliation->product_id,
                            'product_variant_id' => $reconciliation->product_variant_id,
                            'quantity' => $reconciliation->variance,
                            'reason' => 'Inventory Count: ' . $model->count_code,
                            'status' => 'approved',
                        ]);
                    }
                }
            }
        });
    }

    protected function generateReconciliationsFromStock(): void
    {
        if (empty($this->branch_id)) {
            return;
        }

        $stocksQuery = Stock::query()
            ->selectRaw('product_id, product_variant_id, SUM(quantity) as system_qty')
            ->where('branch_id', $this->branch_id)
            ->groupBy('product_id', 'product_variant_id');

        $stocks = $stocksQuery->get();

        foreach ($stocks as $stock) {
            InventoryReconciliation::query()->firstOrCreate(
                [
                    'inventory_count_id' => $this->id,
                    'product_id' => $stock->product_id,
                    'product_variant_id' => $stock->product_variant_id,
                ],
                [
                    'system_qty' => (float) $stock->system_qty,
                    'physical_qty' => 0,
                    'variance' => 0,
                ]
            );
        }
    }

    public function inventoryReconciliations() {
        return $this->hasMany(InventoryReconciliation::class, 'inventory_count_id');
    }

    //
    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
