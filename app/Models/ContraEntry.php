<?php

namespace App\Models;

use App\Traits\HasBranch;

use App\Traits\BelongsToFinancialYear;

use Illuminate\Database\Eloquent\Model;

class ContraEntry extends Model
{
    use HasBranch;

    use BelongsToFinancialYear;
    protected $guarded = [];

    //
    public function fromAccount() {
        return $this->belongsTo(ChartOfAccount::class, 'from_account_id');
    }
    public function toAccount() {
        return $this->belongsTo(ChartOfAccount::class, 'to_account_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
