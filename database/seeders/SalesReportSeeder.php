<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class SalesReportSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('sales_reports')->truncate();
        
        $branchId = DB::table('branches')->first()->id ?? 1;

        $reports = [
            ['branch_id' => $branchId, 'report_date' => now()->subDays(2)->format('Y-m-d'), 'total_sales' => 125000.00],
            ['branch_id' => $branchId, 'report_date' => now()->subDays(1)->format('Y-m-d'), 'total_sales' => 140000.00],
            ['branch_id' => $branchId, 'report_date' => now()->format('Y-m-d'), 'total_sales' => 95000.00],
        ];
        
        foreach($reports as $r) {
            DB::table('sales_reports')->insert($r);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
