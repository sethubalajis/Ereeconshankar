<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class GrnSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('grns')->insert([
                'purchase_order_id' => DB::table('purchase_orders')->inRandomOrder()->value('id') ?? 1,
                'vendor_id' => DB::table('vendors')->inRandomOrder()->value('id') ?? 1,
                'branch_id' => DB::table('branches')->inRandomOrder()->value('id') ?? 1,
                'grn_number' => $faker->word . ' ' . $faker->word,
                'receive_date' => now()->subDays(rand(1, 30)),
                'status' => 'active',
            ]);
        }
    }
}
