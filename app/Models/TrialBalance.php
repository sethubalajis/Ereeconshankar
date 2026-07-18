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

    public static function calculateTrialBalances($asOfDate)
    {
        $ledgers = Ledger::selectRaw('chart_of_account_id, sum(debit) as total_debit, sum(credit) as total_credit')
            ->whereDate('transaction_date', '<=', $asOfDate)
            ->groupBy('chart_of_account_id')
            ->get()
            ->keyBy('chart_of_account_id');

        $journals = JournalEntry::selectRaw('journal_entries.chart_of_account_id, sum(journal_entries.debit) as total_debit, sum(journal_entries.credit) as total_credit')
            ->join('journals', 'journal_entries.journal_id', '=', 'journals.id')
            ->whereDate('journals.journal_date', '<=', $asOfDate)
            ->groupBy('journal_entries.chart_of_account_id')
            ->get()
            ->keyBy('chart_of_account_id');

        $allAccountIds = $ledgers->keys()->merge($journals->keys())->unique();

        foreach ($allAccountIds as $accountId) {
            $ledgerDebit = $ledgers->get($accountId)->total_debit ?? 0;
            $ledgerCredit = $ledgers->get($accountId)->total_credit ?? 0;

            $journalDebit = $journals->get($accountId)->total_debit ?? 0;
            $journalCredit = $journals->get($accountId)->total_credit ?? 0;

            $totalDebit = $ledgerDebit + $journalDebit;
            $totalCredit = $ledgerCredit + $journalCredit;

            self::updateOrCreate(
                [
                    'as_of_date' => $asOfDate,
                    'chart_of_account_id' => $accountId,
                ],
                [
                    'debit_balance' => $totalDebit,
                    'credit_balance' => $totalCredit,
                ]
            );
        }
    }
}
