<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class UserBranchAccessSeeder extends Seeder
{
    public function run(): void
    {
        $branches = DB::table('branches')->get();
        foreach ($branches as $index => $b) {
            DB::table('user_branch_accesses')->insertOrIgnore([
                'user_id' => 1,
                'branch_id' => $b->id,
                'is_default' => $index === 0,
            ]);
        }
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('user_branch_accesses')->insertOrIgnore([
                'user_id' => DB::table('users')->inRandomOrder()->value('id') ?? 1,
                'branch_id' => DB::table('branches')->inRandomOrder()->value('id') ?? 1,
                'is_default' => false,
            ]);
        }
    }
}
