<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class GstReturnSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('gst_returns')->insert([
                'return_type' => $faker->word . ' ' . $faker->word,
                'period_start' => now()->subDays(rand(1, 30)),
                'period_end' => now()->subDays(rand(1, 30)),
                'filing_date' => now()->subDays(rand(1, 30)),
                'arn_number' => $faker->word . ' ' . $faker->word,
                'status' => 'active',
            ]);
        }
    }
}
