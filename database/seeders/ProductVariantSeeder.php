<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ProductVariantSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('product_variants')->truncate();
        
        $products = DB::table('products')->get();
        if($products->isEmpty()) return;

        $variants = [
            ['product_id' => $products[0]->id ?? 1, 'color_id' => 2, 'size_id' => 3, 'sku' => 'SKU-SHCLS01-M-BLU', 'mrp' => 1599.00, 'selling_price' => 1400.00, 'cost_price' => 800.00, 'barcode' => '890123456001', 'is_active' => true],
            ['product_id' => $products[0]->id ?? 1, 'color_id' => 2, 'size_id' => 4, 'sku' => 'SKU-SHCLS01-L-BLU', 'mrp' => 1599.00, 'selling_price' => 1400.00, 'cost_price' => 800.00, 'barcode' => '890123456002', 'is_active' => true],
            ['product_id' => $products[1]->id ?? 2, 'color_id' => 4, 'size_id' => 9, 'sku' => 'SKU-DNSLM02-32-BLK', 'mrp' => 2499.00, 'selling_price' => 2100.00, 'cost_price' => 1200.00, 'barcode' => '890123456003', 'is_active' => true],
            ['product_id' => $products[2]->id ?? 3, 'color_id' => 1, 'size_id' => null, 'sku' => 'SKU-SRKNC03-STD', 'mrp' => 18000.00, 'selling_price' => 17500.00, 'cost_price' => 11000.00, 'barcode' => '890123456004', 'is_active' => true],
        ];
        
        foreach($variants as $v) {
            DB::table('product_variants')->insert($v);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
