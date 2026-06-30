<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class DashboardWidgetSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('dashboard_widgets')->insert([
                'name' => $faker->word . ' ' . $faker->word,
                'type' => $faker->word . ' ' . $faker->word,
                'config' => $faker->sentence,
                'status' => true,
            ]);
        }
    }
}
