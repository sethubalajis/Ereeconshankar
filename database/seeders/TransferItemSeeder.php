<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class TransferItemSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('transfer_items')->truncate();
        
        $transfers = DB::table('stock_transfers')->pluck('id')->toArray();
        if(empty($transfers)) { $transfers = [1]; }
        
        $productId = DB::table('products')->first()->id ?? 1;
        
        $items = [
            ['stock_transfer_id' => $transfers[0] ?? 1, 'product_id' => $productId, 'product_variant_id' => null, 'quantity' => 50],
        ];
        
        foreach($items as $i) {
            DB::table('transfer_items')->insert($i);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
