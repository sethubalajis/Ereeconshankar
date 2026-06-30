<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class SalaryStructureSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('salary_structures')->insert([
                'name' => $faker->word . ' ' . $faker->word,
                'code' => substr(strtoupper($faker->unique()->bothify('???###')), 0, 3),
                'basic_percentage' => rand(1, 9),
                'hra_percentage' => rand(1, 9),
                'status' => true,
            ]);
        }
    }
}
