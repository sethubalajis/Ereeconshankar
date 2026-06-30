<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class BranchTargetSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('branch_targets')->insert([
                'branch_id' => DB::table('branches')->inRandomOrder()->value('id') ?? 1,
                'target_month' => now()->subDays(rand(1, 30)),
                'target_amount' => rand(1, 9),
                'achieved_amount' => rand(1, 9),
            ]);
        }
    }
}
