<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class StockAdjustmentSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('stock_adjustments')->insert([
                'branch_id' => DB::table('branches')->inRandomOrder()->value('id') ?? 1,
                'warehouse_id' => DB::table('warehouses')->inRandomOrder()->value('id') ?? 1,
                'product_id' => DB::table('products')->inRandomOrder()->value('id') ?? 1,
                'product_variant_id' => DB::table('product_variants')->inRandomOrder()->value('id') ?? 1,
                'quantity' => rand(1, 9),
                'reason' => $faker->word . ' ' . $faker->word,
                'status' => 'active',
            ]);
        }
    }
}
