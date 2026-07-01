<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class BranchInventorySeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('branch_inventories')->truncate();
        
        $productId = DB::table('products')->first()->id ?? 1;

        $inventories = [
            ['branch_id' => 1, 'product_id' => $productId, 'product_variant_id' => null, 'quantity' => 150],
            ['branch_id' => 1, 'product_id' => $productId, 'product_variant_id' => null, 'quantity' => 300],
        ];
        
        foreach($inventories as $inv) {
            DB::table('branch_inventories')->insert($inv);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
