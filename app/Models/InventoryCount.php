<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryCount extends Model
{
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
            if ($model->isDirty('status') && $model->status === 'approved') {
                $inventoryService = new \App\Services\InventoryService();
                
                foreach ($model->inventoryReconciliations as $reconciliation) {
                    if ($reconciliation->variance != 0) {
                        $type = $reconciliation->variance > 0 ? 'in' : 'out';
                        $absQuantity = abs($reconciliation->variance);
                        
                        $inventoryService->updateStock(
                            $reconciliation->product_id,
                            $reconciliation->product_variant_id,
                            $model->branch_id,
                            $model->warehouse_id,
                            $type,
                            $absQuantity,
                            0, // Cost could be derived, left 0 for reconciliation adjustment
                            'REC-' . $model->id,
                            'App\\Models\\InventoryCount'
                        );
                    }
                }
            }
        });
    }

    public function inventoryReconciliations() {
        return $this->hasMany(InventoryReconciliation::class, 'inventory_count_id');
    }

    //
    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    public function warehouse() {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
}
