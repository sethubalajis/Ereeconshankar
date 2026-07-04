<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CustomerWalletSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('customer_wallets')->insert([
                'customer_id' => DB::table('customers')->inRandomOrder()->value('id') ?? 1,
                'transaction_type' => $faker->randomElement(['credit', 'debit']),
                'amount' => $faker->randomFloat(2, 10, 1000),
                'description' => $faker->sentence(3),
                'transaction_date' => now()->subDays(rand(1, 30)),
            ]);
        }
    }
}
