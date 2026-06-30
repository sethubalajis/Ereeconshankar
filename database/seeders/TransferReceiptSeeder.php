<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class TransferReceiptSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('transfer_receipts')->insert([
                'stock_transfer_id' => DB::table('stock_transfers')->inRandomOrder()->value('id') ?? 1,
                'receive_date' => now()->subDays(rand(1, 30)),
                'received_by' => rand(1, 9),
                'status' => 'active',
            ]);
        }
    }
}
