<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class KpiReportSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('kpi_reports')->insert([
                'kpi_name' => $faker->word . ' ' . $faker->word,
                'report_date' => now()->subDays(rand(1, 30)),
                'value' => rand(1, 9),
            ]);
        }
    }
}
