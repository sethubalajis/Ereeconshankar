<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class StockReservationSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('stock_reservations')->insert([
                'product_id' => DB::table('products')->inRandomOrder()->value('id') ?? 1,
                'product_variant_id' => DB::table('product_variants')->inRandomOrder()->value('id') ?? 1,
                'branch_id' => DB::table('branches')->inRandomOrder()->value('id') ?? 1,
                'quantity' => rand(1, 9),
                'reference_id' => 1,
                'status' => 'active',
            ]);
        }
    }
}
