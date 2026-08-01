<?php

namespace App\Models;

use App\Traits\BelongsToFinancialYear;

use Illuminate\Database\Eloquent\Model;

class ContraEntry extends Model
{
    use BelongsToFinancialYear;
    protected $guarded = [];

    //
    public function fromAccount() {
        return $this->belongsTo(ChartOfAccount::class, 'from_account_id');
    }
    public function toAccount() {
        return $this->belongsTo(ChartOfAccount::class, 'to_account_id');
    }
}
