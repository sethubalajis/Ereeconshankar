<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PayrollCycleSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('payroll_cycles')->insert([
                'name' => $faker->word . ' ' . $faker->word,
                'start_date' => now()->subDays(rand(1, 30)),
                'end_date' => now()->subDays(rand(1, 30)),
                'payment_date' => now()->subDays(rand(1, 30)),
                'status' => 'active',
            ]);
        }
    }
}
