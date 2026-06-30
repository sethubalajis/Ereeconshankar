<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ShiftSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('shifts')->insert([
                'name' => $faker->word . ' ' . $faker->word,
                'start_time' => now()->format('H:i:s'),
                'end_time' => now()->format('H:i:s'),
                'status' => true,
            ]);
        }
    }
}
