<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PosTerminalSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('pos_terminals')->insert([
                'branch_id' => DB::table('branches')->inRandomOrder()->value('id') ?? 1,
                'terminal_name' => $faker->word . ' ' . $faker->word,
                'terminal_code' => substr(strtoupper($faker->unique()->bothify('???###')), 0, 3),
                'status' => true,
            ]);
        }
    }
}
