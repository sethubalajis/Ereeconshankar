<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class MerchandisePlanningSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('merchandise_plannings')->truncate();
        
        $fyId = DB::table('financial_years')->inRandomOrder()->value('id');
        if (!$fyId) {
            $fyId = DB::table('financial_years')->insertGetId([
                'name' => 'FY 2026-2027',
                'start_date' => '2026-04-01',
                'end_date' => '2027-03-31',
                'is_active' => true,
                'is_closed' => false,
            ]);
        }

        $seasons = DB::table('seasons')->pluck('id')->toArray();
        if (empty($seasons)) {
            $seasons = [1, 2, 3];
        }

        $plans = [
            ['season_id' => $seasons[0] ?? 1, 'target_sales' => 5000000.00, 'planned_budget' => 3500000.00],
            ['season_id' => $seasons[1] ?? 2, 'target_sales' => 8500000.00, 'planned_budget' => 6000000.00],
            ['season_id' => $seasons[2] ?? 3, 'target_sales' => 4500000.00, 'planned_budget' => 3000000.00],
        ];

        foreach ($plans as $plan) {
            DB::table('merchandise_plannings')->insert([
                'financial_year_id' => $fyId,
                'season_id' => $plan['season_id'],
                'target_sales' => $plan['target_sales'],
                'planned_budget' => $plan['planned_budget'],
            ]);
        }
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
