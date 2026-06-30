<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class BuyXGetYOfferSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('buy_x_get_y_offers')->insert([
                'buy_product_id' => 1,
                'get_product_id' => 1,
                'buy_quantity' => rand(1, 9),
                'get_quantity' => rand(1, 9),
                'start_date' => now()->subDays(rand(1, 30)),
                'end_date' => now()->subDays(rand(1, 30)),
                'status' => true,
            ]);
        }
    }
}
