<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PackingListSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('packing_lists')->insert([
                'picking_list_id' => DB::table('picking_lists')->inRandomOrder()->value('id') ?? 1,
                'status' => 'active',
            ]);
        }
    }
}
