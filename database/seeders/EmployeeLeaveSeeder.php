<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class EmployeeLeaveSeeder extends Seeder {
    public function run(): void {
        \Schema::disableForeignKeyConstraints();
        DB::table('employee_leaves')->truncate();
        
        $empId = DB::table('employees')->first()->id ?? 1;
        $leaveTypeId = DB::table('leave_types')->first()->id ?? 1;

        $eleaves = [
            ['employee_id' => $empId, 'leave_type_id' => $leaveTypeId, 'start_date' => now()->addDays(5)->format('Y-m-d'), 'end_date' => now()->addDays(6)->format('Y-m-d'), 'status' => 'approved', 'reason' => 'Family function'],
        ];
        
        foreach($eleaves as $e) {
            DB::table('employee_leaves')->insert($e);
        }
        \Schema::enableForeignKeyConstraints();
    }
}
