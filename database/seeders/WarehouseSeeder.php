<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class WarehouseSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('warehouses')->truncate();
        
        $warehouses = [
            ['branch_id' => 1, 'name' => 'Main Central Warehouse', 'code' => 'WH-MC1', 'address' => 'Central Industrial Park, Chennai', 'capacity' => 100000.00, 'status' => true],
            ['branch_id' => 1, 'name' => 'Secondary Overflow Godown', 'code' => 'WH-SO1', 'address' => 'North Chennai Depot', 'capacity' => 50000.00, 'status' => true],
        ];
        
        foreach($warehouses as $wh) {
            DB::table('warehouses')->insert($wh);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
