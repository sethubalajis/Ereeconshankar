<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PickingListSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('picking_lists')->insert([
                'warehouse_id' => DB::table('warehouses')->inRandomOrder()->value('id') ?? 1,
                'reference_id' => 1,
                'reference_type' => $faker->word . ' ' . $faker->word,
                'status' => 'active',
            ]);
        }
    }
}
