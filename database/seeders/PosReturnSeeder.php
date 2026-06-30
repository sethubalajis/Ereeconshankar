<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PosReturnSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('pos_returns')->insert([
                'pos_sale_id' => DB::table('pos_sales')->inRandomOrder()->value('id') ?? 1,
                'return_date' => now()->subDays(rand(1, 30)),
                'return_amount' => rand(1, 9),
                'reason' => $faker->sentence,
                'status' => 'active',
            ]);
        }
    }
}
