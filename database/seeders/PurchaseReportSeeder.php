<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PurchaseReportSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            $date = now()->subDays(rand(1, 30));
            DB::table('purchase_reports')->insert([
                'branch_id' => DB::table('branches')->inRandomOrder()->value('id') ?? 1,
                'from_date' => $date->format('Y-m-d'),
                'to_date' => $date->format('Y-m-d'),
                'report_date' => $date->format('Y-m-d'),
                'total_purchases' => rand(1000, 9000),
            ]);
        }
    }
}
