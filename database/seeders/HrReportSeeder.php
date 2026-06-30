<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class HrReportSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('hr_reports')->insert([
                'report_month' => now()->subDays(rand(1, 30)),
                'total_employees' => rand(1, 9),
                'new_hires' => rand(1, 9),
                'exits' => rand(1, 9),
            ]);
        }
    }
}
