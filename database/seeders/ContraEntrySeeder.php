<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ContraEntrySeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('contra_entries')->insert([
                'date' => now()->subDays(rand(1, 30)),
                'from_account_id' => 1,
                'to_account_id' => 1,
                'amount' => rand(1, 9),
                'description' => $faker->word . ' ' . $faker->word,
            ]);
        }
    }
}
