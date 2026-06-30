<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosReturn extends Model
{
    protected $guarded = [];

    //
    public function posSale() {
        return $this->belongsTo(PosSale::class, 'pos_sale_id');
    }
}
