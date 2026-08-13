<?php

namespace App\Models;

use App\Traits\HasBranch;

use Illuminate\Database\Eloquent\Model;

class Rack extends Model
{
    use HasBranch;

    protected $guarded = [];

    protected static function booted(): void
    {
        static::creating(function (self $rack): void {
            if (empty($rack->code)) {
                $lastRack = self::orderBy('id', 'desc')->first();
                $nextId = $lastRack ? $lastRack->id + 1 : 1;
                $rack->code = 'RCK-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    //
    public function warehouseZone() {
        return $this->belongsTo(WarehouseZone::class, 'warehouse_zone_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
