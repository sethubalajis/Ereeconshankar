<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class TransferApprovalSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('transfer_approvals')->insert([
                'transfer_request_id' => DB::table('transfer_requests')->inRandomOrder()->value('id') ?? 1,
                'approver_id' => 1,
                'approval_date' => now()->subDays(rand(1, 30)),
                'status' => 'active',
            ]);
        }
    }
}
