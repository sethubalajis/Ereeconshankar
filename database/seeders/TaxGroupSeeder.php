<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class TaxGroupSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('tax_groups')->insert([
                'name' => $faker->word . ' ' . $faker->word,
                'description' => $faker->sentence,
                'total_tax_percentage' => rand(1, 9),
            ]);
        }
    }
}
