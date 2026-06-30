<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class TransferRequestSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('transfer_requests')->insert([
                'from_branch_id' => 1,
                'to_branch_id' => 1,
                'request_date' => now()->subDays(rand(1, 30)),
                'status' => 'active',
            ]);
        }
    }
}
