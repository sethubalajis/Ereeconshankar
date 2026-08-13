<?php

namespace App\Models;

use App\Traits\HasBranch;

use Illuminate\Database\Eloquent\Model;

class CashOpening extends Model
{
    use HasBranch;

    protected $guarded = [];

    //
    public function cashRegister() {
        return $this->belongsTo(CashRegister::class, 'cash_register_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
