<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class WarehouseTransferSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('warehouse_transfers')->insert([
                'from_warehouse_id' => 1,
                'to_warehouse_id' => 1,
                'transfer_date' => now()->subDays(rand(1, 30)),
                'status' => 'active',
            ]);
        }
    }
}
