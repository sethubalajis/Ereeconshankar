<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class SalaryComponentSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('salary_components')->insert([
                'name' => $faker->word . ' ' . $faker->word,
                'type' => $faker->word . ' ' . $faker->word,
                'is_taxable' => true,
                'status' => true,
            ]);
        }
    }
}
