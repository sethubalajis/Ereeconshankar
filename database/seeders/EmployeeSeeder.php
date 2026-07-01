<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class EmployeeSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('employees')->truncate();
        
        $userId = DB::table('users')->first()->id ?? 1;
        $branchId = DB::table('branches')->first()->id ?? 1;
        $deptId = DB::table('departments')->first()->id ?? 1;
        $desigId = DB::table('designations')->first()->id ?? 1;

        $emps = [
            ['user_id' => $userId, 'department_id' => $deptId, 'designation_id' => $desigId, 'branch_id' => $branchId, 'employee_code' => 'EMP-001', 'first_name' => 'Rajesh', 'last_name' => 'Kumar', 'dob' => '1990-05-15', 'joining_date' => '2020-01-10', 'pan_number' => 'ABCDE1234F', 'pf_number' => 'PF-MH-12345', 'esi_number' => 'ESI-98765', 'status' => 'active'],
        ];
        
        foreach($emps as $e) {
            DB::table('employees')->insert($e);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
