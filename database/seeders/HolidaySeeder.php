<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class HolidaySeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('holidays')->insert([
                'name' => $faker->word . ' ' . $faker->word,
                'date' => now()->subDays(rand(1, 30)),
                'description' => $faker->sentence,
                'is_restricted' => true,
            ]);
        }
    }
}
