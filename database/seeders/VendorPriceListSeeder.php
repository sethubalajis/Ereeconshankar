<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class VendorPriceListSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('vendor_price_lists')->insert([
                'vendor_id' => DB::table('vendors')->inRandomOrder()->value('id') ?? 1,
                'product_id' => DB::table('products')->inRandomOrder()->value('id') ?? 1,
                'product_variant_id' => DB::table('product_variants')->inRandomOrder()->value('id') ?? 1,
                'agreed_price' => rand(1, 9),
                'valid_from' => now()->subDays(rand(1, 30)),
                'valid_to' => now()->subDays(rand(1, 30)),
            ]);
        }
    }
}
