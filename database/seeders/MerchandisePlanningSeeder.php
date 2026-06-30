<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class MerchandisePlanningSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('merchandise_plannings')->insert([
                'financial_year_id' => DB::table('financial_years')->inRandomOrder()->value('id') ?? 1,
                'season_id' => DB::table('seasons')->inRandomOrder()->value('id') ?? 1,
                'target_sales' => rand(1, 9),
                'planned_budget' => rand(1, 9),
            ]);
        }
    }
}
