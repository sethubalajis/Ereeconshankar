<?php

namespace App\Models;

use App\Traits\HasBranch;

use App\Traits\BelongsToFinancialYear;

use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    use HasBranch;

    use BelongsToFinancialYear;
    protected $guarded = [];

    protected static function booted()
    {
        static::saved(function ($entry) {
            if ($entry->journal && $entry->journal->journal_date) {
                TrialBalance::calculateTrialBalances($entry->journal->journal_date);
            }
        });

        static::deleted(function ($entry) {
            if ($entry->journal && $entry->journal->journal_date) {
                TrialBalance::calculateTrialBalances($entry->journal->journal_date);
            }
        });
    }

    //
    public function journal() {
        return $this->belongsTo(Journal::class, 'journal_id');
    }
    public function chartOfAccount() {
        return $this->belongsTo(ChartOfAccount::class, 'chart_of_account_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
