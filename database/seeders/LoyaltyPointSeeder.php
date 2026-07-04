<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class LoyaltyPointSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('loyalty_points')->insert([
                'customer_id' => DB::table('customers')->inRandomOrder()->value('id') ?? 1,
                'transaction_type' => $faker->randomElement(['earn', 'redeem']),
                'points' => $faker->numberBetween(10, 500),
                'description' => $faker->sentence(3),
                'transaction_date' => now()->subDays(rand(1, 30)),
            ]);
        }
    }
}
