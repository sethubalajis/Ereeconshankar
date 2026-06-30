<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class LoyaltyProgramSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('loyalty_programs')->insert([
                'name' => $faker->word . ' ' . $faker->word,
                'code' => substr(strtoupper($faker->unique()->bothify('???###')), 0, 3),
                'points_per_amount' => rand(1, 9),
                'minimum_points_to_redeem' => rand(1, 9),
                'value_per_point' => rand(1, 9),
                'status' => true,
            ]);
        }
    }
}
