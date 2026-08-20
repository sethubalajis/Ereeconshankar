<?php

namespace App\Services;

use App\Models\JournalEntry;
use App\Models\Ledger;
use App\Models\Journal;
use Illuminate\Support\Facades\DB;
use Exception;

class AccountingService
{
    /**
     * Post a Journal Entry ensuring total debits equal total credits.
     *
     * @param array $journalData Data for the journal record
     * @param array $entries Array of entries with 'chart_of_account_id', 'debit', 'credit', 'branch_id', 'company_id', 'financial_year_id'
     * @return Journal
     * @throws Exception
     */
    public function postJournalEntry(array $journalData, array $entries)
    {
        $totalDebit = collect($entries)->sum('debit');
        $totalCredit = collect($entries)->sum('credit');

        if (round($totalDebit, 2) !== round($totalCredit, 2)) {
            throw new Exception("Journal Entry unbalanced: Total Debit ($totalDebit) does not equal Total Credit ($totalCredit).");
        }

        return DB::transaction(function () use ($journalData, $entries) {
            $journal = Journal::create($journalData);

            foreach ($entries as $entryData) {
                $entryData['journal_id'] = $journal->id;
                $journalEntry = JournalEntry::create($entryData);
                
                // Update Ledger
                $this->postLedgerEntry($journalEntry);
            }

            return $journal;
        });
    }

    /**
     * Post to Ledger based on a Journal Entry.
     */
    protected function postLedgerEntry(JournalEntry $entry)
    {
        Ledger::create([
            'chart_of_account_id' => $entry->chart_of_account_id,
            'journal_entry_id' => $entry->id,
            'debit' => $entry->debit,
            'credit' => $entry->credit,
            'branch_id' => $entry->branch_id,
            'company_id' => $entry->company_id,
            'financial_year_id' => $entry->financial_year_id,
            'transaction_date' => $entry->created_at,
        ]);
    }
}
