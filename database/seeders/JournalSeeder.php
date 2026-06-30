<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class JournalSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('journals')->insert([
                'journal_number' => $faker->word . ' ' . $faker->word,
                'journal_date' => now()->subDays(rand(1, 30)),
                'reference' => $faker->word . ' ' . $faker->word,
                'total_debit' => rand(1, 9),
                'total_credit' => rand(1, 9),
                'notes' => $faker->sentence,
                'status' => 'active',
            ]);
        }
    }
}
