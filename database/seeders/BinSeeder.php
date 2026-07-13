<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class BinSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('bins')->truncate();
        
        $bins = [
            ['rack_id' => 1, 'name' => 'Bin A1-01', 'code' => 'BIN-A101', 'max_weight' => 500.00],
            ['rack_id' => 1, 'name' => 'Bin A1-02', 'code' => 'BIN-A102', 'max_weight' => 500.00],
            ['rack_id' => 2, 'name' => 'Bin A2-01', 'code' => 'BIN-A201', 'max_weight' => 500.00],
            ['rack_id' => 4, 'name' => 'Pick Bin FP1-01', 'code' => 'BIN-FP101', 'max_weight' => 100.00],
        ];
        
        foreach($bins as $b) {
            DB::table('bins')->insert($b);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
