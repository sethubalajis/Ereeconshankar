import os

seeders = {}

seeders['DepartmentSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class DepartmentSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('departments')->truncate();
        
        $branchId = DB::table('branches')->first()->id ?? 1;

        $depts = [
            ['branch_id' => $branchId, 'name' => 'Human Resources', 'code' => 'HR', 'description' => 'HR & Admin', 'status' => true],
            ['branch_id' => $branchId, 'name' => 'Sales & Retail', 'code' => 'SALES', 'description' => 'Store Front Sales', 'status' => true],
            ['branch_id' => $branchId, 'name' => 'IT Support', 'code' => 'IT', 'description' => 'Technical support', 'status' => true],
        ];
        
        foreach($depts as $d) {
            DB::table('departments')->insert($d);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['DesignationSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class DesignationSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('designations')->truncate();
        
        $desigs = [
            ['name' => 'Store Manager', 'code' => 'SMGR', 'description' => 'Manages retail store operations', 'status' => true],
            ['name' => 'Sales Executive', 'code' => 'SALEX', 'description' => 'Handles direct customer sales', 'status' => true],
            ['name' => 'HR Manager', 'code' => 'HRMGR', 'description' => 'Human resources manager', 'status' => true],
        ];
        
        foreach($desigs as $d) {
            DB::table('designations')->insert($d);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['EmployeeSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
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
"""

seeders['ShiftSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class ShiftSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('shifts')->truncate();
        
        $shifts = [
            ['name' => 'Morning Shift', 'start_time' => '09:00:00', 'end_time' => '18:00:00', 'status' => true],
            ['name' => 'Evening Shift', 'start_time' => '14:00:00', 'end_time' => '23:00:00', 'status' => true],
        ];
        
        foreach($shifts as $s) {
            DB::table('shifts')->insert($s);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['AttendanceSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class AttendanceSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('attendances')->truncate();
        
        $empId = DB::table('employees')->first()->id ?? 1;

        $atts = [
            ['employee_id' => $empId, 'date' => now()->subDays(1)->format('Y-m-d'), 'check_in' => '09:05:00', 'check_out' => '18:10:00', 'status' => 'present'],
            ['employee_id' => $empId, 'date' => now()->format('Y-m-d'), 'check_in' => '08:55:00', 'check_out' => null, 'status' => 'present'],
        ];
        
        foreach($atts as $a) {
            DB::table('attendances')->insert($a);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['LeaveTypeSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class LeaveTypeSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('leave_types')->truncate();
        
        $leaves = [
            ['name' => 'Sick Leave', 'code' => 'SL', 'days_allowed' => 12, 'status' => true],
            ['name' => 'Casual Leave', 'code' => 'CL', 'days_allowed' => 12, 'status' => true],
            ['name' => 'Earned Leave', 'code' => 'EL', 'days_allowed' => 15, 'status' => true],
        ];
        
        foreach($leaves as $l) {
            DB::table('leave_types')->insert($l);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['EmployeeLeaveSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class EmployeeLeaveSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('employee_leaves')->truncate();
        
        $empId = DB::table('employees')->first()->id ?? 1;
        $leaveTypeId = DB::table('leave_types')->first()->id ?? 1;

        $eleaves = [
            ['employee_id' => $empId, 'leave_type_id' => $leaveTypeId, 'start_date' => now()->addDays(5)->format('Y-m-d'), 'end_date' => now()->addDays(6)->format('Y-m-d'), 'status' => 'approved', 'reason' => 'Family function'],
        ];
        
        foreach($eleaves as $e) {
            DB::table('employee_leaves')->insert($e);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['SalaryStructureSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class SalaryStructureSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('salary_structures')->truncate();
        
        $structs = [
            ['name' => 'Standard Executive Structure', 'code' => 'STR-EXEC', 'basic_percentage' => 40.00, 'hra_percentage' => 20.00, 'status' => true],
        ];
        
        foreach($structs as $s) {
            DB::table('salary_structures')->insert($s);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['PayrollCycleSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class PayrollCycleSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('payroll_cycles')->truncate();
        
        $cycles = [
            ['name' => 'June 2026 Payroll', 'start_date' => '2026-06-01', 'end_date' => '2026-06-30', 'payment_date' => '2026-07-05', 'status' => 'processed'],
            ['name' => 'July 2026 Payroll', 'start_date' => '2026-07-01', 'end_date' => '2026-07-31', 'payment_date' => '2026-08-05', 'status' => 'draft'],
        ];
        
        foreach($cycles as $c) {
            DB::table('payroll_cycles')->insert($c);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

for name, content in seeders.items():
    with open(f"database/seeders/{name}", "w", encoding="utf-8") as f:
        f.write(content)

print("HR seeders created.")
