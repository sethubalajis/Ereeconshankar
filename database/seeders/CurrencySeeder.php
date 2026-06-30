<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('currencies')->insert([
                'name' => $faker->word . ' ' . $faker->word,
                'code' => substr(strtoupper($faker->unique()->bothify('???###')), 0, 3),
                'symbol' => $faker->randomElement(['$', '€', '₹', '£', '¥']),
                'exchange_rate' => rand(1, 9),
                'is_default' => true,
            ]);
        }
    }
}
