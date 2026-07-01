import os

seeders = {}

seeders['DashboardWidgetSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class DashboardWidgetSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('dashboard_widgets')->truncate();
        
        $widgets = [
            ['name' => 'Monthly Sales Overview', 'type' => 'chart', 'config' => json_encode(['chartType' => 'bar', 'metric' => 'sales']), 'status' => true],
            ['name' => 'Top Performing Products', 'type' => 'table', 'config' => json_encode(['limit' => 5, 'sort' => 'desc']), 'status' => true],
            ['name' => 'Pending Services', 'type' => 'stat', 'config' => json_encode(['metric' => 'open_tickets']), 'status' => true],
        ];
        
        foreach($widgets as $w) {
            DB::table('dashboard_widgets')->insert($w);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['SalesReportSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
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
"""

seeders['KpiReportSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
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
"""

seeders['InventoryReportSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class InventoryReportSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('inventory_reports')->truncate();
        
        $branchId = DB::table('branches')->first()->id ?? 1;

        $reports = [
            ['branch_id' => $branchId, 'report_date' => now()->subDays(1)->format('Y-m-d'), 'total_inventory_value' => 2500000.00],
        ];
        
        foreach($reports as $r) {
            DB::table('inventory_reports')->insert($r);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['FinancialReportSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class FinancialReportSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('financial_reports')->truncate();
        
        $reports = [
            ['report_type' => 'Profit & Loss', 'as_of_date' => now()->subMonths(1)->endOfMonth()->format('Y-m-d'), 'data' => json_encode(['revenue' => 1500000.00, 'expenses' => 900000.00, 'net_profit' => 600000.00])],
            ['report_type' => 'Balance Sheet', 'as_of_date' => now()->subMonths(1)->endOfMonth()->format('Y-m-d'), 'data' => json_encode(['assets' => 5000000.00, 'liabilities' => 2000000.00, 'equity' => 3000000.00])],
        ];
        
        foreach($reports as $r) {
            DB::table('financial_reports')->insert($r);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

for name, content in seeders.items():
    with open(f"database/seeders/{name}", "w", encoding="utf-8") as f:
        f.write(content)

print("Reports & BI Dashboard seeders created.")
