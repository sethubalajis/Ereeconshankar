<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PosSaleItemSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('pos_sale_items')->insert([
                'pos_sale_id' => DB::table('pos_sales')->inRandomOrder()->value('id') ?? 1,
                'product_id' => DB::table('products')->inRandomOrder()->value('id') ?? 1,
                'product_variant_id' => DB::table('product_variants')->inRandomOrder()->value('id') ?? 1,
                'quantity' => rand(1, 9),
                'unit_price' => rand(1, 9),
                'discount' => rand(1, 9),
                'total_price' => rand(1, 9),
            ]);
        }
    }
}
