<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class TdsRecordSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('tds_records')->truncate();
        
        $employeeId = DB::table('employees')->first()->id ?? 1;
        $fyId = DB::table('financial_years')->first()->id ?? 1;

        $tds = [
            [
                'employee_id' => $employeeId,
                'financial_year_id' => $fyId,
                'tax_regime' => 'new',
                'annual_gross_salary' => 600000.00,
                'approved_tax_deductions' => 50000.00,
                'taxable_income' => 550000.00,
                'annual_income_tax' => 25000.00,
                'monthly_tds' => 2083.33,
                'remaining_tds' => 25000.00,
                'status' => 'processed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        foreach($tds as $t) {
            DB::table('tds_records')->insert($t);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
