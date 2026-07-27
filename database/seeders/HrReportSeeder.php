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
            $sub = rand(1, 30);
            DB::table('hr_reports')->insert([
                'from_date' => now()->subDays($sub + 15)->format('Y-m-d'),
                'to_date' => now()->subDays($sub)->format('Y-m-d'),
                'report_month' => now()->subDays($sub)->format('Y-m-d'),
                'total_employees' => rand(1, 9),
                'new_hires' => rand(1, 9),
                'exits' => rand(1, 9),
            ]);
        }
    }
}
