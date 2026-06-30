<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ProductVariantSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('product_variants')->insert([
                'product_id' => DB::table('products')->inRandomOrder()->value('id') ?? 1,
                'sku' => $faker->word . ' ' . $faker->word,
                'mrp' => rand(1, 9),
                'selling_price' => rand(1, 9),
                'cost_price' => rand(1, 9),
                'barcode' => substr(strtoupper($faker->unique()->bothify('???###')), 0, 3),
                'is_active' => true,
            ]);
        }
    }
}
