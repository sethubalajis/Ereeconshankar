<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class WarehouseZoneSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('warehouse_zones')->truncate();
        
        $zones = [
            ['warehouse_id' => 1, 'name' => 'Receiving Zone', 'code' => 'WZ-RCV', 'type' => 'inbound'],
            ['warehouse_id' => 1, 'name' => 'Bulk Storage', 'code' => 'WZ-BLK', 'type' => 'storage'],
            ['warehouse_id' => 1, 'name' => 'Picking Zone', 'code' => 'WZ-PCK', 'type' => 'picking'],
            ['warehouse_id' => 1, 'name' => 'Dispatch Zone', 'code' => 'WZ-DSP', 'type' => 'outbound'],
        ];
        
        foreach($zones as $z) {
            DB::table('warehouse_zones')->insert($z);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
