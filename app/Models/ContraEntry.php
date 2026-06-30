<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContraEntry extends Model
{
    protected $guarded = [];

    //
    public function fromAccount() {
        return $this->belongsTo(ChartOfAccount::class, 'from_account_id');
    }
    public function toAccount() {
        return $this->belongsTo(ChartOfAccount::class, 'to_account_id');
    }
}
