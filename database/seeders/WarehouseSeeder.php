<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class WarehouseSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('warehouses')->truncate();
        
        $warehouses = [
            ['branch_id' => 1, 'name' => 'Central Hub Warehouse', 'code' => 'WH-CH1', 'address' => 'Main Industrial Area, Chennai', 'capacity' => 50000.00, 'status' => true],
            ['branch_id' => 1, 'name' => 'North Region Godown', 'code' => 'WH-NR1', 'address' => 'TNHB Colony, Ambattur', 'capacity' => 25000.00, 'status' => true],
            ['branch_id' => 1, 'name' => 'South City Distribution Center', 'code' => 'WH-SC1', 'address' => 'OMR IT Expy, Sholinganallur', 'capacity' => 30000.00, 'status' => true],
        ];
        
        foreach($warehouses as $wh) {
            DB::table('warehouses')->insert($wh);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
