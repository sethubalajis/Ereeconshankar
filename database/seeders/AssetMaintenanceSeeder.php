<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class AssetMaintenanceSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('asset_maintenances')->insert([
                'asset_id' => DB::table('assets')->inRandomOrder()->value('id') ?? 1,
                'maintenance_date' => now()->subDays(rand(1, 30)),
                'cost' => rand(1, 9),
                'description' => $faker->sentence,
            ]);
        }
    }
}
