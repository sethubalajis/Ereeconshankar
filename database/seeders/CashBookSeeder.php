<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CashBookSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('cash_books')->insert([
                'date' => now()->subDays(rand(1, 30)),
                'description' => $faker->word . ' ' . $faker->word,
                'receipt_amount' => rand(1, 9),
                'payment_amount' => rand(1, 9),
                'balance' => rand(1, 9),
            ]);
        }
    }
}
