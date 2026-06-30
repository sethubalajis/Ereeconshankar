<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class BonusSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('bonuses')->insert([
                'employee_id' => DB::table('employees')->inRandomOrder()->value('id') ?? 1,
                'bonus_type' => $faker->word . ' ' . $faker->word,
                'amount' => rand(1, 9),
                'payout_date' => now()->subDays(rand(1, 30)),
                'status' => 'active',
            ]);
        }
    }
}
