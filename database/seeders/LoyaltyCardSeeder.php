<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class LoyaltyCardSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('loyalty_cards')->insert([
                'customer_id' => DB::table('customers')->inRandomOrder()->value('id') ?? 1,
                'card_number' => $faker->word . ' ' . $faker->word,
                'issue_date' => now()->subDays(rand(1, 30)),
                'expiry_date' => now()->subDays(rand(1, 30)),
                'status' => true,
            ]);
        }
    }
}
