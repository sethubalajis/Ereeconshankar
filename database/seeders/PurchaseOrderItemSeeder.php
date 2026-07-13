<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
        
        $variantId = DB::table('product_variants')->where('product_id', $productId)->first()->id ?? null;
        
        $items = [
            ['purchase_order_id' => $pos[0] ?? 1, 'product_id' => $productId, 'product_variant_id' => $variantId, 'quantity' => 500, 'unit_price' => 250.00, 'total_price' => 125000.00],
            ['purchase_order_id' => $pos[1] ?? 2, 'product_id' => $productId, 'product_variant_id' => $variantId, 'quantity' => 300, 'unit_price' => 250.00, 'total_price' => 75000.00],
            ['purchase_order_id' => $pos[2] ?? 3, 'product_id' => $productId, 'product_variant_id' => $variantId, 'quantity' => 1000, 'unit_price' => 250.00, 'total_price' => 250000.00],
        ];
        
        foreach($items as $item) {
            DB::table('purchase_order_items')->insert($item);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
