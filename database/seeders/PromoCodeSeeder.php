<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PromoCodeSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('promo_codes')->insert([
                'code' => substr(strtoupper($faker->unique()->bothify('???###')), 0, 3),
                'discount_percentage' => rand(1, 9),
                'discount_amount' => rand(1, 9),
                'valid_from' => now()->subDays(rand(1, 30)),
                'valid_to' => now()->subDays(rand(1, 30)),
                'status' => true,
            ]);
        }
    }
}
