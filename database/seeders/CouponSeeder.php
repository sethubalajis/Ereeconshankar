<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('coupons')->insert([
                'code' => substr(strtoupper($faker->unique()->bothify('???###')), 0, 3),
                'discount_percentage' => rand(1, 9),
                'discount_amount' => rand(1, 9),
                'start_date' => now()->subDays(rand(1, 30)),
                'end_date' => now()->subDays(rand(1, 30)),
                'min_purchase_amount' => rand(1, 9),
                'max_usage' => rand(1, 9),
                'current_usage' => rand(1, 9),
                'status' => true,
            ]);
        }
    }
}
