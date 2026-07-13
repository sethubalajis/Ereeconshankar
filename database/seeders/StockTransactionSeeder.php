<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
                        'reference_type' => 'App\\Models\\Grn',
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
