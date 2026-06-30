<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackingList extends Model
{
    protected $guarded = [];

    //
    public function pickingList() {
        return $this->belongsTo(PickingList::class, 'picking_list_id');
    }
}
