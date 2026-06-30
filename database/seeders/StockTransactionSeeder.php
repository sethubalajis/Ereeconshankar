<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class StockTransactionSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('stock_transactions')->insert([
                'stock_id' => DB::table('stocks')->inRandomOrder()->value('id') ?? 1,
                'transaction_type' => $faker->word . ' ' . $faker->word,
                'quantity' => rand(1, 9),
                'reference_id' => 1,
                'reference_type' => $faker->word . ' ' . $faker->word,
            ]);
        }
    }
}
