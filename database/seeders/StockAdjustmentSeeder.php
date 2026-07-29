<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class StockAdjustmentSeeder extends Seeder {
    public function run(): void {
        \Schema::disableForeignKeyConstraints();
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
        \Schema::enableForeignKeyConstraints();
    }
}
