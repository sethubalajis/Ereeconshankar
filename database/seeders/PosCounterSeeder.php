<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PosCounterSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('pos_counters')->insert([
                'branch_id' => DB::table('branches')->inRandomOrder()->value('id') ?? 1,
                'counter_name' => $faker->word . ' ' . $faker->word,
                'status' => true,
            ]);
        }
    }
}
