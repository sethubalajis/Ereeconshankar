import os

seeders = {}

seeders['WarehouseTransferSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class WarehouseTransferSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('warehouse_transfers')->truncate();
        
        $transfers = [
            ['from_warehouse_id' => 1, 'to_warehouse_id' => 2, 'transfer_date' => now()->subDays(5), 'status' => 'completed'],
            ['from_warehouse_id' => 1, 'to_warehouse_id' => 3, 'transfer_date' => now()->subDays(2), 'status' => 'in_transit'],
            ['from_warehouse_id' => 3, 'to_warehouse_id' => 1, 'transfer_date' => now(), 'status' => 'pending'],
        ];
        
        foreach($transfers as $t) {
            DB::table('warehouse_transfers')->insert($t);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['StockAdjustmentSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class StockAdjustmentSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('stock_adjustments')->truncate();
        
        $productId = DB::table('products')->first()->id ?? 1;

        $adjustments = [
            ['branch_id' => 1, 'warehouse_id' => 1, 'product_id' => $productId, 'product_variant_id' => null, 'quantity' => -5, 'reason' => 'Damaged during transit', 'status' => 'approved'],
            ['branch_id' => 1, 'warehouse_id' => 2, 'product_id' => $productId, 'product_variant_id' => null, 'quantity' => 2, 'reason' => 'Found in unexpected bin', 'status' => 'approved'],
            ['branch_id' => 1, 'warehouse_id' => 3, 'product_id' => $productId, 'product_variant_id' => null, 'quantity' => -1, 'reason' => 'Quality control failure', 'status' => 'pending'],
        ];
        
        foreach($adjustments as $adj) {
            DB::table('stock_adjustments')->insert($adj);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

for name, content in seeders.items():
    with open(f"database/seeders/{name}", "w", encoding="utf-8") as f:
        f.write(content)

print("Warehouse Management transactional seeders created.")
