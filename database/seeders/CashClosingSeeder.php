<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CashClosingSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('cash_closings')->insert([
                'cash_register_id' => DB::table('cash_registers')->inRandomOrder()->value('id') ?? 1,
                'closing_time' => now(),
                'closing_amount' => rand(1, 9),
                'variance' => rand(1, 9),
            ]);
        }
    }
}
