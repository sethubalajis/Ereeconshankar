<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class AssetAllocationSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('asset_allocations')->insert([
                'asset_id' => DB::table('assets')->inRandomOrder()->value('id') ?? 1,
                'employee_id' => DB::table('employees')->inRandomOrder()->value('id') ?? 1,
                'allocation_date' => now()->subDays(rand(1, 30)),
                'return_date' => now()->subDays(rand(1, 30)),
                'status' => 'active',
            ]);
        }
    }
}
