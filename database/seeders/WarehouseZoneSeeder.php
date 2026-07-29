<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class WarehouseZoneSeeder extends Seeder {
    public function run(): void {
        \Schema::disableForeignKeyConstraints();
        DB::table('warehouse_zones')->truncate();
        
        $wh_id = DB::table('warehouses')->first()->id ?? 1;
        
        $zones = [
            ['warehouse_id' => $wh_id, 'name' => 'Receiving Zone', 'code' => 'Z-REC', 'type' => 'Receiving'],
            ['warehouse_id' => $wh_id, 'name' => 'Bulk Storage Zone', 'code' => 'Z-BLK', 'type' => 'Storage'],
            ['warehouse_id' => $wh_id, 'name' => 'Picking Zone', 'code' => 'Z-PCK', 'type' => 'Storage'],
            ['warehouse_id' => $wh_id, 'name' => 'Returns & QC Zone', 'code' => 'Z-RET', 'type' => 'Returns'],
        ];
        
        foreach($zones as $z) {
            DB::table('warehouse_zones')->insert($z);
        }
        \Schema::enableForeignKeyConstraints();
    }
}
