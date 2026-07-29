<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class InventoryReportSeeder extends Seeder {
    public function run(): void {
        \Schema::disableForeignKeyConstraints();
        DB::table('inventory_reports')->truncate();
        
        $branchId = DB::table('branches')->first()->id ?? 1;

        $date = now()->subDays(1)->format('Y-m-d');
        $reports = [
            [
                'branch_id' => $branchId,
                'from_date' => $date,
                'to_date' => $date,
                'report_date' => $date,
                'total_inventory_value' => 2500000.00
            ],
        ];
        
        foreach($reports as $r) {
            DB::table('inventory_reports')->insert($r);
        }
        \Schema::enableForeignKeyConstraints();
    }
}
