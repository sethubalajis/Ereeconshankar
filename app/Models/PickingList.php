<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PickingList extends Model
{
    protected $guarded = [];

    //
    public function warehouse() {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
}
