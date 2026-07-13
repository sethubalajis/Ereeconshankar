<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class KpiReportSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('kpi_reports')->truncate();
        
        $kpis = [
            ['kpi_name' => 'Customer Retention Rate', 'report_date' => now()->subMonths(1)->endOfMonth()->format('Y-m-d'), 'value' => 78.50],
            ['kpi_name' => 'Average Order Value', 'report_date' => now()->subMonths(1)->endOfMonth()->format('Y-m-d'), 'value' => 2500.00],
            ['kpi_name' => 'Inventory Turnover', 'report_date' => now()->subMonths(1)->endOfMonth()->format('Y-m-d'), 'value' => 4.20],
        ];
        
        foreach($kpis as $k) {
            DB::table('kpi_reports')->insert($k);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
