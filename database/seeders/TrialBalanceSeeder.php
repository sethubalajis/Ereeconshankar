<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class TrialBalanceSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('trial_balances')->insert([
                'as_of_date' => now()->subDays(rand(1, 30)),
                'chart_of_account_id' => DB::table('chart_of_accounts')->inRandomOrder()->value('id') ?? 1,
                'debit_balance' => rand(1, 9),
                'credit_balance' => rand(1, 9),
            ]);
        }
    }
}
