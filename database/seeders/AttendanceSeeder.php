<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class AttendanceSeeder extends Seeder {
    public function run(): void {
        \Schema::disableForeignKeyConstraints();
        DB::table('attendances')->truncate();
        
        $empId = DB::table('employees')->first()->id ?? 1;

        $atts = [
            ['employee_id' => $empId, 'date' => now()->subDays(1)->format('Y-m-d'), 'check_in' => '09:05:00', 'check_out' => '18:10:00', 'status' => 'present'],
            ['employee_id' => $empId, 'date' => now()->format('Y-m-d'), 'check_in' => '08:55:00', 'check_out' => null, 'status' => 'present'],
        ];
        
        foreach($atts as $a) {
            DB::table('attendances')->insert($a);
        }
        \Schema::enableForeignKeyConstraints();
    }
}
