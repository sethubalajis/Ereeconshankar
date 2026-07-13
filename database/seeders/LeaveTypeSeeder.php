<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
