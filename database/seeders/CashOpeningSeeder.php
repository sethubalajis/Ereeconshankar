<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CashOpeningSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('cash_openings')->insert([
                'cash_register_id' => DB::table('cash_registers')->inRandomOrder()->value('id') ?? 1,
                'opening_time' => now(),
                'opening_amount' => rand(1, 9),
            ]);
        }
    }
}
