<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class BranchExpenseSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('branch_expenses')->truncate();
        
        $expenses = [
            ['branch_id' => 1, 'expense_date' => now()->subDays(2), 'amount' => 5000.00, 'description' => 'Monthly Electricity Bill', 'status' => 'approved'],
            ['branch_id' => 1, 'expense_date' => now()->subDays(5), 'amount' => 1500.00, 'description' => 'Office Stationery and Printing', 'status' => 'approved'],
            ['branch_id' => 1, 'expense_date' => now()->subDays(1), 'amount' => 3500.00, 'description' => 'Store Maintenance and Cleaning', 'status' => 'pending'],
        ];
        
        foreach($expenses as $e) {
            DB::table('branch_expenses')->insert($e);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
