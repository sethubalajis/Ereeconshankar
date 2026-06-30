<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class WarehouseZoneSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('warehouse_zones')->insert([
                'warehouse_id' => DB::table('warehouses')->inRandomOrder()->value('id') ?? 1,
                'name' => $faker->word . ' ' . $faker->word,
                'code' => substr(strtoupper($faker->unique()->bothify('???###')), 0, 3),
                'type' => $faker->word . ' ' . $faker->word,
            ]);
        }
    }
}
