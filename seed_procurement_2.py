import os

seeders = {}

seeders['PurchaseOrderSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class PurchaseOrderSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('purchase_orders')->truncate();
        
        $vendors = DB::table('vendors')->pluck('id')->toArray();
        if(empty($vendors)) { $vendors = [1, 2, 3]; }
        
        $orders = [
            ['vendor_id' => $vendors[0] ?? 1, 'branch_id' => 1, 'po_number' => 'PO2026001', 'po_date' => now()->subDays(10), 'expected_delivery_date' => now()->addDays(5), 'status' => 'approved', 'total_amount' => 125000.00],
            ['vendor_id' => $vendors[1] ?? 2, 'branch_id' => 1, 'po_number' => 'PO2026002', 'po_date' => now()->subDays(2), 'expected_delivery_date' => now()->addDays(15), 'status' => 'pending', 'total_amount' => 75000.00],
            ['vendor_id' => $vendors[2] ?? 3, 'branch_id' => 1, 'po_number' => 'PO2026003', 'po_date' => now()->subDays(5), 'expected_delivery_date' => now()->addDays(2), 'status' => 'completed', 'total_amount' => 250000.00],
        ];
        
        foreach($orders as $o) {
            DB::table('purchase_orders')->insert($o);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['PurchaseOrderItemSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class PurchaseOrderItemSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('purchase_order_items')->truncate();
        
        $pos = DB::table('purchase_orders')->pluck('id')->toArray();
        if(empty($pos)) { $pos = [1, 2, 3]; }
        
        // Ensure a dummy product exists to avoid foreign key errors
        $productId = DB::table('products')->first()->id ?? DB::table('products')->insertGetId([
            'name' => 'Premium Cotton Fabric',
            'code' => 'PCF-01'
        ]);
        
        $items = [
            ['purchase_order_id' => $pos[0] ?? 1, 'product_id' => $productId, 'product_variant_id' => null, 'quantity' => 500, 'unit_price' => 250.00, 'total_price' => 125000.00],
            ['purchase_order_id' => $pos[1] ?? 2, 'product_id' => $productId, 'product_variant_id' => null, 'quantity' => 300, 'unit_price' => 250.00, 'total_price' => 75000.00],
            ['purchase_order_id' => $pos[2] ?? 3, 'product_id' => $productId, 'product_variant_id' => null, 'quantity' => 1000, 'unit_price' => 250.00, 'total_price' => 250000.00],
        ];
        
        foreach($items as $item) {
            DB::table('purchase_order_items')->insert($item);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

for name, content in seeders.items():
    with open(f"database/seeders/{name}", "w", encoding="utf-8") as f:
        f.write(content)

print("Procurement transactional seeders created.")
