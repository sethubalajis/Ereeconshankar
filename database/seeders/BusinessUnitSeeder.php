<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class BusinessUnitSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('business_units')->insert([
                'company_id' => DB::table('companies')->inRandomOrder()->value('id') ?? 1,
                'name' => $faker->word . ' ' . $faker->word,
                'code' => substr(strtoupper($faker->unique()->bothify('???###')), 0, 3),
                'description' => $faker->sentence,
                'status' => true,
            ]);
        }
    }
}
