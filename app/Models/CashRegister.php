<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashRegister extends Model
{
    protected $guarded = [];

    //
    public function posTerminal() {
        return $this->belongsTo(PosTerminal::class, 'pos_terminal_id');
    }
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
