<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CashRegisterSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('cash_registers')->insert([
                'pos_terminal_id' => DB::table('pos_terminals')->inRandomOrder()->value('id') ?? 1,
                'user_id' => DB::table('users')->inRandomOrder()->value('id') ?? 1,
                'opening_balance' => rand(1, 9),
                'closing_balance' => rand(1, 9),
                'status' => 'active',
            ]);
        }
    }
}
