<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class FinancialYearSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('financial_years')->insert([
                'name' => $faker->word . ' ' . $faker->word,
                'start_date' => now()->subDays(rand(1, 30)),
                'end_date' => now()->subDays(rand(1, 30)),
                'is_active' => true,
                'is_closed' => true,
            ]);
        }
    }
}
