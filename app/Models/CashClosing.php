<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashClosing extends Model
{
    protected $guarded = [];

    //
    public function cashRegister() {
        return $this->belongsTo(CashRegister::class, 'cash_register_id');
    }
}
