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
            ['branch_id' => 1, 'name' => 'Main Central Warehouse', 'code' => 'WH-MC1', 'address' => 'Central Industrial Park, Chennai', 'capacity' => 100000.00, 'status' => true],
            ['branch_id' => 1, 'name' => 'Secondary Overflow Godown', 'code' => 'WH-SO1', 'address' => 'North Chennai Depot', 'capacity' => 50000.00, 'status' => true],
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
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['GrnSeeder.php'] = """<?php
namespace Database\\Seeders;

use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
use App\\Models\\Grn;

class GrnSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('grns')->truncate();
        
        // Truncate stock to rebuild based on GRNs
        DB::table('stocks')->truncate();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $po = DB::table('purchase_orders')->first();
        $vendorId = DB::table('vendors')->first()->id ?? 1;
        $branchId = DB::table('branches')->first()->id ?? 1;
        $warehouseId = DB::table('warehouses')->first()->id ?? 1;
        
        $products = DB::table('products')->take(3)->get();
        if ($products->count() < 3) {
            return;
        }

        $grns = [
            [
                'purchase_order_id' => $po->id ?? 1,
                'vendor_id' => $vendorId,
                'branch_id' => $branchId,
                'warehouse_id' => $warehouseId,
                'receive_date' => now()->subDays(10)->format('Y-m-d'),
                'status' => 'received',
                'received_items' => [
                    [
                        'product_id' => $products[0]->id,
                        'product_variant_id' => null,
                        'quantity' => 200,
                        'unit_price' => 1250.00
                    ],
                    [
                        'product_id' => $products[1]->id,
                        'product_variant_id' => null,
                        'quantity' => 150,
                        'unit_price' => 850.50
                    ]
                ]
            ],
            [
                'purchase_order_id' => $po->id ?? 1,
                'vendor_id' => $vendorId,
                'branch_id' => $branchId,
                'warehouse_id' => $warehouseId,
                'receive_date' => now()->subDays(5)->format('Y-m-d'),
                'status' => 'received',
                'received_items' => [
                    [
                        'product_id' => $products[2]->id,
                        'product_variant_id' => null,
                        'quantity' => 500,
                        'unit_price' => 45.00
                    ],
                    [
                        'product_id' => $products[0]->id,
                        'product_variant_id' => null,
                        'quantity' => 50,
                        'unit_price' => 1300.00
                    ]
                ]
            ]
        ];

        foreach ($grns as $grnData) {
            // Using Eloquent create so the created event triggers and updates stocks table
            Grn::create($grnData);
        }
    }
}
"""

seeders['StockTransactionSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class StockTransactionSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('stock_transactions')->truncate();
        
        $grns = DB::table('grns')->get();
        $transactions = [];
        
        foreach ($grns as $grn) {
            $items = json_decode($grn->received_items, true) ?? [];
            foreach ($items as $item) {
                $stock = DB::table('stocks')
                    ->where('product_id', $item['product_id'])
                    ->where('branch_id', $grn->branch_id)
                    ->first();
                    
                if ($stock) {
                    $transactions[] = [
                        'stock_id' => $stock->id,
                        'transaction_type' => 'in',
                        'quantity' => $item['quantity'],
                        'reference' => 'GRN-' . $grn->id,
                        'reference_type' => 'App\\\\Models\\\\Grn',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }
        
        if (count($transactions) > 0) {
            DB::table('stock_transactions')->insert($transactions);
        }
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

for name, content in seeders.items():
    with open(f"database/seeders/{name}", "w", encoding="utf-8") as f:
        f.write(content)

print("Warehouse & Inventory seeders created successfully.")
