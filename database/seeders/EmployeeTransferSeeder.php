<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class EmployeeTransferSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('employee_transfers')->insert([
                'employee_id' => DB::table('employees')->inRandomOrder()->value('id') ?? 1,
                'from_branch_id' => 1,
                'to_branch_id' => 1,
                'transfer_date' => now()->subDays(rand(1, 30)),
                'status' => 'active',
            ]);
        }
    }
}
