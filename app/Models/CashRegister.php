<?php

namespace App\Models;

use App\Traits\HasBranch;

use Illuminate\Database\Eloquent\Model;

class CashRegister extends Model
{
    use HasBranch;

    protected $guarded = [];

    //
    public function posTerminal() {
        return $this->belongsTo(PosTerminal::class, 'pos_terminal_id');
    }
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getNameAttribute()
    {
        return 'Register #' . $this->id . ($this->posTerminal ? ' - ' . $this->posTerminal->terminal_name : '');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
