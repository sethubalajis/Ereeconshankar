<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class RackSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('racks')->truncate();
        
        $racks = [
            ['warehouse_zone_id' => 2, 'name' => 'Aisle A - Rack 1', 'code' => 'RCK-A1'],
            ['warehouse_zone_id' => 2, 'name' => 'Aisle A - Rack 2', 'code' => 'RCK-A2'],
            ['warehouse_zone_id' => 2, 'name' => 'Aisle B - Rack 1', 'code' => 'RCK-B1'],
            ['warehouse_zone_id' => 3, 'name' => 'Fast Pick Rack 1', 'code' => 'RCK-FP1'],
        ];
        
        foreach($racks as $r) {
            DB::table('racks')->insert($r);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
