<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class StockTransferSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('stock_transfers')->insert([
                'transfer_request_id' => DB::table('transfer_requests')->inRandomOrder()->value('id') ?? 1,
                'from_branch_id' => 1,
                'to_branch_id' => 1,
                'transfer_date' => now()->subDays(rand(1, 30)),
                'vehicle_number' => $faker->word . ' ' . $faker->word,
                'driver_name' => $faker->word . ' ' . $faker->word,
                'status' => 'active',
            ]);
        }
    }
}
