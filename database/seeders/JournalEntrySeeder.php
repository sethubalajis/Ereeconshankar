<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class JournalEntrySeeder extends Seeder {
    public function run(): void {
        \Schema::disableForeignKeyConstraints();
        DB::table('journal_entries')->truncate();
        
        $jv1 = DB::table('journals')->where('journal_number', 'JV-2026-001')->value('id') ?? 1;
        $jv2 = DB::table('journals')->where('journal_number', 'JV-2026-002')->value('id') ?? 2;
        
        $cashAcc = DB::table('chart_of_accounts')->where('account_code', '1001')->value('id') ?? 1;
        $bankAcc = DB::table('chart_of_accounts')->where('account_code', '1002')->value('id') ?? 2;
        $rentAcc = DB::table('chart_of_accounts')->where('account_code', '5001')->value('id') ?? 4;
        
        $entries = [
            // JV 1: Rent Payment (Debit Rent, Credit Bank)
            ['journal_id' => $jv1, 'chart_of_account_id' => $rentAcc, 'description' => 'Rent Expense June', 'debit' => 25000.00, 'credit' => 0.00],
            ['journal_id' => $jv1, 'chart_of_account_id' => $bankAcc, 'description' => 'HDFC Payment', 'debit' => 0.00, 'credit' => 25000.00],
            // JV 2: Cash Deposit (Debit Bank, Credit Cash)
            ['journal_id' => $jv2, 'chart_of_account_id' => $bankAcc, 'description' => 'Bank Deposit', 'debit' => 10000.00, 'credit' => 0.00],
            ['journal_id' => $jv2, 'chart_of_account_id' => $cashAcc, 'description' => 'Cash reduction', 'debit' => 0.00, 'credit' => 10000.00],
        ];
        
        foreach($entries as $e) {
            DB::table('journal_entries')->insert($e);
        }
        \Schema::enableForeignKeyConstraints();
    }
}
