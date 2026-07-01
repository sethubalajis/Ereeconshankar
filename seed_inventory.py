import os

seeders = {}

seeders['WarehouseSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
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
"""

seeders['WarehouseZoneSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
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
"""

seeders['RackSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
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
"""

seeders['BinSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
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
"""

seeders['StockSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class StockSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('stocks')->truncate();
        
        $productId = DB::table('products')->first()->id ?? 1;

        $stocks = [
            ['product_id' => $productId, 'product_variant_id' => null, 'branch_id' => 1, 'warehouse_id' => 1, 'quantity' => 1500, 'avg_cost' => 250.00],
            ['product_id' => $productId, 'product_variant_id' => null, 'branch_id' => 1, 'warehouse_id' => 2, 'quantity' => 300, 'avg_cost' => 250.00],
            ['product_id' => $productId, 'product_variant_id' => null, 'branch_id' => 1, 'warehouse_id' => 3, 'quantity' => 850, 'avg_cost' => 250.00],
        ];
        
        foreach($stocks as $s) {
            DB::table('stocks')->insert($s);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

for name, content in seeders.items():
    with open(f"database/seeders/{name}", "w", encoding="utf-8") as f:
        f.write(content)

print("Inventory seeders created.")
