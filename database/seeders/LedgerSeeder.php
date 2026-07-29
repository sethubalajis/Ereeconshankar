<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class LedgerSeeder extends Seeder {
    public function run(): void {
        \Schema::disableForeignKeyConstraints();
        DB::table('ledgers')->truncate();
        
        $cashAcc = DB::table('chart_of_accounts')->where('account_code', '1001')->value('id') ?? 1;
        $bankAcc = DB::table('chart_of_accounts')->where('account_code', '1002')->value('id') ?? 2;
        $rentAcc = DB::table('chart_of_accounts')->where('account_code', '5001')->value('id') ?? 4;
        
        $ledgers = [
            // Simulating posting JV1
            ['chart_of_account_id' => $rentAcc, 'transaction_date' => now()->subDays(5), 'description' => 'Rent Payment for June', 'debit' => 25000.00, 'credit' => 0.00, 'balance' => 25000.00],
            ['chart_of_account_id' => $bankAcc, 'transaction_date' => now()->subDays(5), 'description' => 'Rent Payment for June', 'debit' => 0.00, 'credit' => 25000.00, 'balance' => 225000.00], // 250k - 25k
        ];
        
        foreach($ledgers as $l) {
            DB::table('ledgers')->insert($l);
        }
        \Schema::enableForeignKeyConstraints();
    }
}
