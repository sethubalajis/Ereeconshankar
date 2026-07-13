<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class BuyXGetYOfferSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('buy_x_get_y_offers')->truncate();
        
        $p1 = DB::table('products')->first()->id ?? 1;
        $p2 = DB::table('products')->skip(1)->first()->id ?? 2;

        $offers = [
            ['buy_product_id' => $p1, 'get_product_id' => $p1, 'buy_quantity' => 2, 'get_quantity' => 1, 'start_date' => now()->subDays(10), 'end_date' => now()->addDays(20), 'status' => true],
            ['buy_product_id' => $p1, 'get_product_id' => $p2, 'buy_quantity' => 1, 'get_quantity' => 1, 'start_date' => now(), 'end_date' => now()->addDays(15), 'status' => true],
        ];
        
        foreach($offers as $o) {
            DB::table('buy_x_get_y_offers')->insert($o);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
