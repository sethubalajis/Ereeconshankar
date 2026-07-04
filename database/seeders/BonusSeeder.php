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
        $bonusTypes = ['Performance Bonus', 'Diwali Bonus', 'Year-End Bonus', 'Joining Bonus', 'Referral Bonus', 'Retention Bonus'];
        
        for ($i = 0; $i < 10; $i++) {
            $employeeId = DB::table('employees')->inRandomOrder()->value('id');
            if (!$employeeId) continue;

            DB::table('bonuses')->insert([
                'employee_id' => $employeeId,
                'bonus_type' => $faker->randomElement($bonusTypes),
                'amount' => $faker->randomFloat(2, 1000, 50000),
                'payout_date' => $faker->dateTimeBetween('-1 year', '+1 month')->format('Y-m-d'),
                'status' => $faker->randomElement(['pending', 'approved', 'paid']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
