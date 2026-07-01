<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class TransitInventorySeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('transit_inventories')->truncate();
        
        $transfers = DB::table('stock_transfers')->where('status', 'dispatched')->pluck('id')->toArray();
        if(empty($transfers)) { $transfers = [1]; }
        
        $productId = DB::table('products')->first()->id ?? 1;
        
        $transit = [
            ['stock_transfer_id' => $transfers[0] ?? 1, 'product_id' => $productId, 'product_variant_id' => null, 'quantity' => 50],
        ];
        
        foreach($transit as $t) {
            DB::table('transit_inventories')->insert($t);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
