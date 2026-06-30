<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrialBalance extends Model
{
    protected $guarded = [];

    //
    public function chartOfAccount() {
        return $this->belongsTo(ChartOfAccount::class, 'chart_of_account_id');
    }
}
