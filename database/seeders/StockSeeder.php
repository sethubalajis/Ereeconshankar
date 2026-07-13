<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
