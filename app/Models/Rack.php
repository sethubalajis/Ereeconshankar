<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rack extends Model
{
    protected $guarded = [];

    //
    public function warehouseZone() {
        return $this->belongsTo(WarehouseZone::class, 'warehouse_zone_id');
    }
}
