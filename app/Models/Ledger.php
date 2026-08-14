<?php

namespace App\Models;

use App\Traits\HasBranch;

use Illuminate\Database\Eloquent\Model;

class Ledger extends Model
{
    use HasBranch;

    protected $guarded = [];

    protected static function booted()
    {
        static::saved(function ($ledger) {
            if ($ledger->transaction_date) {
                TrialBalance::calculateTrialBalances($ledger->transaction_date);
            }
        });

        static::deleted(function ($ledger) {
            if ($ledger->transaction_date) {
                TrialBalance::calculateTrialBalances($ledger->transaction_date);
            }
        });
    }

    //
    public function chartOfAccount() {
        return $this->belongsTo(ChartOfAccount::class, 'chart_of_account_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
