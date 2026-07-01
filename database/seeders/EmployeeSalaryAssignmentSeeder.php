<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class EmployeeSalaryAssignmentSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('employee_salary_assignments')->truncate();
        
        $empId = DB::table('employees')->first()->id ?? 1;
        $structId = DB::table('salary_structures')->first()->id ?? 1;

        $assignments = [
            ['employee_id' => $empId, 'salary_structure_id' => $structId, 'gross_salary' => 50000.00, 'effective_from' => '2025-01-01'],
        ];
        
        foreach($assignments as $a) {
            DB::table('employee_salary_assignments')->insert($a);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
