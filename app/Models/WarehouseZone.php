<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseZone extends Model
{
    protected $guarded = [];

    protected static function booted(): void
    {
        static::creating(function (self $warehouseZone): void {
            if (empty($warehouseZone->code)) {
                $lastZone = self::orderBy('id', 'desc')->first();
                $nextId = $lastZone ? $lastZone->id + 1 : 1;
                $warehouseZone->code = 'WHZ-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    //
    public function warehouse() {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
}
