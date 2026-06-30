<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ProductBarcodeSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('product_barcodes')->insert([
                'product_id' => DB::table('products')->inRandomOrder()->value('id') ?? 1,
                'product_variant_id' => DB::table('product_variants')->inRandomOrder()->value('id') ?? 1,
                'barcode' => substr(strtoupper($faker->unique()->bothify('???###')), 0, 3),
                'barcode_type' => substr(strtoupper($faker->unique()->bothify('???###')), 0, 3),
            ]);
        }
    }
}
