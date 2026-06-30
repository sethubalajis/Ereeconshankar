<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class UserBranchAccessSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('user_branch_accesses')->insert([
                'user_id' => DB::table('users')->inRandomOrder()->value('id') ?? 1,
                'branch_id' => DB::table('branches')->inRandomOrder()->value('id') ?? 1,
                'is_default' => true,
            ]);
        }
    }
}
