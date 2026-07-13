<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class VendorPriceListSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('vendor_price_lists')->truncate();
        
        $vendors = DB::table('vendors')->pluck('id')->toArray();
        if(empty($vendors)) { $vendors = [1, 2, 3]; }
        
        $productId = DB::table('products')->first()->id ?? 1;

        $priceLists = [
            ['vendor_id' => $vendors[0] ?? 1, 'product_id' => $productId, 'product_variant_id' => null, 'agreed_price' => 250.00, 'valid_from' => now()->startOfYear(), 'valid_to' => now()->endOfYear()],
            ['vendor_id' => $vendors[1] ?? 2, 'product_id' => $productId, 'product_variant_id' => null, 'agreed_price' => 245.00, 'valid_from' => now()->subMonths(6), 'valid_to' => now()->addMonths(6)],
            ['vendor_id' => $vendors[2] ?? 3, 'product_id' => $productId, 'product_variant_id' => null, 'agreed_price' => 260.00, 'valid_from' => now()->subMonths(1), 'valid_to' => now()->addMonths(11)],
        ];

        foreach ($priceLists as $price) {
            DB::table('vendor_price_lists')->insert($price);
        }
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
