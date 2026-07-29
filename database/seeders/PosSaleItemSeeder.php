<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class PosSaleItemSeeder extends Seeder {
    public function run(): void {
        \Schema::disableForeignKeyConstraints();
        DB::table('pos_sale_items')->truncate();
        
        $sales = DB::table('pos_sales')->pluck('id')->toArray();
        if(empty($sales)) { $sales = [1, 2]; }
        $productId = DB::table('products')->first()->id ?? 1;
        
        $items = [
            ['pos_sale_id' => $sales[0] ?? 1, 'product_id' => $productId, 'product_variant_id' => null, 'quantity' => 2, 'unit_price' => 750.00, 'discount' => 25.00, 'total_price' => 1450.00],
            ['pos_sale_id' => $sales[1] ?? 2, 'product_id' => $productId, 'product_variant_id' => null, 'quantity' => 1, 'unit_price' => 2500.00, 'discount' => 100.00, 'total_price' => 2400.00],
        ];
        
        foreach($items as $i) {
            DB::table('pos_sale_items')->insert($i);
        }
        \Schema::enableForeignKeyConstraints();
    }
}
