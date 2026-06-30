<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class TdsRecordSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('tds_records')->insert([
                'section_code' => substr(strtoupper($faker->unique()->bothify('???###')), 0, 3),
                'tds_percentage' => rand(1, 9),
                'deductee_id' => 1,
                'deductee_type' => $faker->word . ' ' . $faker->word,
                'amount' => rand(1, 9),
            ]);
        }
    }
}
