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
                'name' => $faker->unique()->randomElement(['Basic', 'Silver', 'Gold', 'Platinum', 'Diamond', 'VIP']),
                'code' => strtoupper($faker->unique()->lexify('???')),
                'fee' => $faker->numberBetween(100, 5000),
                'duration_months' => $faker->randomElement([1, 3, 6, 12, 24]),
                'benefits_description' => $faker->sentence,
                'status' => true,
            ]);
        }
    }
}
