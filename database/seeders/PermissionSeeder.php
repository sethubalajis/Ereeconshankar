<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('permissions')->insert([
                'name' => $faker->word . ' ' . $faker->word,
                'module' => $faker->word . ' ' . $faker->word,
                'description' => $faker->sentence,
            ]);
        }
    }
}
