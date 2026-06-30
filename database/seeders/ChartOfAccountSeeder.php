<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ChartOfAccountSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('chart_of_accounts')->insert([
                'account_group_id' => DB::table('account_groups')->inRandomOrder()->value('id') ?? 1,
                'account_code' => substr(strtoupper($faker->unique()->bothify('???###')), 0, 3),
                'account_name' => $faker->word . ' ' . $faker->word,
                'account_type' => $faker->word . ' ' . $faker->word,
                'opening_balance' => rand(1, 9),
                'current_balance' => rand(1, 9),
            ]);
        }
    }
}
