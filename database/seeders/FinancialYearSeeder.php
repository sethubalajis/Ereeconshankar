<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class FinancialYearSeeder extends Seeder {
    public function run(): void {
        \Schema::disableForeignKeyConstraints();
        DB::table('financial_years')->truncate();

        $years = [
            ['name' => 'FY 2024-2025', 'start_date' => '2024-04-01', 'end_date' => '2025-03-31', 'is_active' => false, 'is_closed' => true],
            ['name' => 'FY 2025-2026', 'start_date' => '2025-04-01', 'end_date' => '2026-03-31', 'is_active' => true, 'is_closed' => false],
            ['name' => 'FY 2026-2027', 'start_date' => '2026-04-01', 'end_date' => '2027-03-31', 'is_active' => false, 'is_closed' => false],
        ];
        
        foreach($years as $y) {
            DB::table('financial_years')->insert($y);
        }
        \Schema::enableForeignKeyConstraints();
    }
}
