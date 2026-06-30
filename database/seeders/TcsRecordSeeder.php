<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class TcsRecordSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('tcs_records')->insert([
                'section_code' => substr(strtoupper($faker->unique()->bothify('???###')), 0, 3),
                'tcs_percentage' => rand(1, 9),
                'collectee_id' => 1,
                'collectee_type' => $faker->word . ' ' . $faker->word,
                'amount' => rand(1, 9),
            ]);
        }
    }
}
