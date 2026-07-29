<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ChartOfAccountSeeder extends Seeder {
    public function run(): void {
        \Schema::disableForeignKeyConstraints();
        DB::table('chart_of_accounts')->truncate();
        
        $cAstId = DB::table('account_groups')->where('code', 'C-AST')->value('id') ?? 1;
        $revId = DB::table('account_groups')->where('code', 'REV')->value('id') ?? 4;
        $expId = DB::table('account_groups')->where('code', 'EXP')->value('id') ?? 5;

        $coas = [
            ['account_group_id' => $cAstId, 'account_code' => '1001', 'account_name' => 'Cash on Hand', 'account_type' => 'asset', 'opening_balance' => 50000.00, 'current_balance' => 50000.00],
            ['account_group_id' => $cAstId, 'account_code' => '1002', 'account_name' => 'HDFC Current Account', 'account_type' => 'asset', 'opening_balance' => 250000.00, 'current_balance' => 250000.00],
            ['account_group_id' => $revId, 'account_code' => '4001', 'account_name' => 'Sales Revenue', 'account_type' => 'revenue', 'opening_balance' => 0.00, 'current_balance' => 0.00],
            ['account_group_id' => $expId, 'account_code' => '5001', 'account_name' => 'Rent Expense', 'account_type' => 'expense', 'opening_balance' => 0.00, 'current_balance' => 0.00],
        ];
        
        foreach($coas as $coa) {
            DB::table('chart_of_accounts')->insert($coa);
        }
        \Schema::enableForeignKeyConstraints();
    }
}
