<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class BankAccountSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('bank_accounts')->insert([
                'bank_name' => $faker->word . ' ' . $faker->word,
                'account_number' => $faker->word . ' ' . $faker->word,
                'ifsc_code' => substr(strtoupper($faker->unique()->bothify('???###')), 0, 3),
                'branch_name' => $faker->word . ' ' . $faker->word,
                'account_type' => $faker->word . ' ' . $faker->word,
                'opening_balance' => rand(1, 9),
                'current_balance' => rand(1, 9),
                'is_default' => true,
            ]);
        }
    }
}
