<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class InventoryReportSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('inventory_reports')->insert([
                'branch_id' => DB::table('branches')->inRandomOrder()->value('id') ?? 1,
                'report_date' => now()->subDays(rand(1, 30)),
                'total_inventory_value' => rand(1, 9),
            ]);
        }
    }
}
