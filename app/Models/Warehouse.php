<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $guarded = [];

    protected $casts = [
        'status' => 'boolean',
        'is_default' => 'boolean',
        'is_damaged' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $warehouse): void {
            if (empty($warehouse->code)) {
                $lastWarehouse = self::orderBy('id', 'desc')->first();
                $nextId = $lastWarehouse ? $lastWarehouse->id + 1 : 1;
                $warehouse->code = 'WH-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            }
        });

        static::saved(function (self $warehouse): void {
            if (! $warehouse->is_default || ! $warehouse->branch_id) {
                return;
            }

            self::query()
                ->where('branch_id', $warehouse->branch_id)
                ->whereKeyNot($warehouse->getKey())
                ->update(['is_default' => false]);
        });
    }

    //
    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public static function defaultIdForBranch(int $branchId): ?int
    {
        return self::query()
            ->where('branch_id', $branchId)
            ->where('is_default', true)
            ->value('id');
    }

    public static function getDamagedWarehouseForBranch(int $branchId): ?self
    {
        $warehouse = self::query()
            ->where('branch_id', $branchId)
            ->where('is_damaged', true)
            ->first();

        if (! $warehouse) {
            $branch = Branch::find($branchId);
            $warehouse = self::create([
                'branch_id' => $branchId,
                'name' => 'Damaged Goods - ' . ($branch ? $branch->name : 'Branch ' . $branchId),
                'is_damaged' => true,
                'is_default' => false,
                'status' => true,
            ]);
        }

        return $warehouse;
    }
}
