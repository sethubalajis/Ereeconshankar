<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class FinancialReportSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('financial_reports')->insert([
                'report_type' => $faker->word . ' ' . $faker->word,
                'as_of_date' => now()->subDays(rand(1, 30)),
                'data' => $faker->sentence,
            ]);
        }
    }
}
