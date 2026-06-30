<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PurchaseReturnSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('purchase_returns')->insert([
                'purchase_order_id' => DB::table('purchase_orders')->inRandomOrder()->value('id') ?? 1,
                'grn_id' => DB::table('grns')->inRandomOrder()->value('id') ?? 1,
                'vendor_id' => DB::table('vendors')->inRandomOrder()->value('id') ?? 1,
                'branch_id' => DB::table('branches')->inRandomOrder()->value('id') ?? 1,
                'return_date' => now()->subDays(rand(1, 30)),
                'total_amount' => rand(1, 9),
                'reason' => $faker->sentence,
                'status' => 'active',
            ]);
        }
    }
}
