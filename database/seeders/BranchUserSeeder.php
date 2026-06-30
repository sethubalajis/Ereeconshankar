<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class BranchUserSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('branch_users')->insert([
                'branch_id' => DB::table('branches')->inRandomOrder()->value('id') ?? 1,
                'user_id' => DB::table('users')->inRandomOrder()->value('id') ?? 1,
            ]);
        }
    }
}
