<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class HsnSacCodeSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('hsn_sac_codes')->insert([
                'code' => substr(strtoupper($faker->unique()->bothify('???###')), 0, 3),
                'type' => $faker->word . ' ' . $faker->word,
                'description' => $faker->sentence,
            ]);
        }
    }
}
