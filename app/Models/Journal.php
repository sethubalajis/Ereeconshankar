<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function ($journal) {
            if (empty($journal->journal_number)) {
                $latest = static::where('journal_number', 'like', 'JRN-%')->latest('id')->first();
                $next = $latest ? intval(str_replace('JRN-', '', $latest->journal_number)) + 1 : 1;
                $journal->journal_number = 'JRN-' . str_pad($next, 4, '0', STR_PAD_LEFT);
            }
        });

        static::saved(function ($journal) {
            if ($journal->journal_date) {
                TrialBalance::calculateTrialBalances($journal->journal_date);
            }
        });

        static::deleted(function ($journal) {
            if ($journal->journal_date) {
                TrialBalance::calculateTrialBalances($journal->journal_date);
            }
        });
    }

    //
}
