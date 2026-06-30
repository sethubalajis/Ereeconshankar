<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class JournalEntrySeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('journal_entries')->insert([
                'journal_id' => DB::table('journals')->inRandomOrder()->value('id') ?? 1,
                'chart_of_account_id' => DB::table('chart_of_accounts')->inRandomOrder()->value('id') ?? 1,
                'description' => $faker->word . ' ' . $faker->word,
                'debit' => rand(1, 9),
                'credit' => rand(1, 9),
            ]);
        }
    }
}
