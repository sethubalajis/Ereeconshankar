<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class WarrantyClaimSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('warranty_claims')->insert([
                'customer_id' => DB::table('customers')->inRandomOrder()->value('id') ?? 1,
                'product_id' => DB::table('products')->inRandomOrder()->value('id') ?? 1,
                'claim_number' => $faker->word . ' ' . $faker->word,
                'claim_date' => now()->subDays(rand(1, 30)),
                'status' => 'active',
            ]);
        }
    }
}
