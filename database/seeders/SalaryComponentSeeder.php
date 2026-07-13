<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class SalaryComponentSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('salary_components')->truncate();
        
        $components = [
            ['name' => 'Basic Pay', 'type' => 'earning', 'is_taxable' => true, 'status' => true],
            ['name' => 'House Rent Allowance (HRA)', 'type' => 'earning', 'is_taxable' => false, 'status' => true],
            ['name' => 'Special Allowance', 'type' => 'earning', 'is_taxable' => true, 'status' => true],
            ['name' => 'Provident Fund (PF)', 'type' => 'deduction', 'is_taxable' => false, 'status' => true],
            ['name' => 'Professional Tax (PT)', 'type' => 'deduction', 'is_taxable' => false, 'status' => true],
        ];
        
        foreach($components as $c) {
            DB::table('salary_components')->insert($c);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
