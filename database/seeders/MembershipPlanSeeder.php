<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class MembershipPlanSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('membership_plans')->insert([
                'name' => $faker->word . ' ' . $faker->word,
                'code' => substr(strtoupper($faker->unique()->bothify('???###')), 0, 3),
                'fee' => rand(1, 9),
                'duration_months' => rand(1, 9),
                'benefits_description' => $faker->sentence,
                'status' => true,
            ]);
        }
    }
}
