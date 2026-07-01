<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
