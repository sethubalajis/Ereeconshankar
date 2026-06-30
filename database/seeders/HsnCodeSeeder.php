<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class HsnCodeSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('hsn_codes')->insert([
                'hsn_code' => substr(strtoupper($faker->unique()->bothify('???###')), 0, 3),
                'description' => $faker->sentence,
            ]);
        }
    }
}
