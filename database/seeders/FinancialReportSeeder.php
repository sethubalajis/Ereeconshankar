<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class FinancialReportSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('financial_reports')->truncate();
        
        $reports = [
            ['report_type' => 'Profit & Loss', 'from_date' => now()->subMonths(1)->startOfMonth()->format('Y-m-d'), 'to_date' => now()->subMonths(1)->endOfMonth()->format('Y-m-d'), 'as_of_date' => now()->subMonths(1)->endOfMonth()->format('Y-m-d'), 'data' => json_encode(['revenue' => 1500000.00, 'expenses' => 900000.00, 'net_profit' => 600000.00])],
            ['report_type' => 'Balance Sheet', 'from_date' => now()->subMonths(1)->startOfMonth()->format('Y-m-d'), 'to_date' => now()->subMonths(1)->endOfMonth()->format('Y-m-d'), 'as_of_date' => now()->subMonths(1)->endOfMonth()->format('Y-m-d'), 'data' => json_encode(['assets' => 5000000.00, 'liabilities' => 2000000.00, 'equity' => 3000000.00])],
        ];
        
        foreach($reports as $r) {
            DB::table('financial_reports')->insert($r);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
