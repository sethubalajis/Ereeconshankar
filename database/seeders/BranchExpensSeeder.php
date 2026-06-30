<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class BranchExpensSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('branch_expenses')->insert([
                'branch_id' => DB::table('branches')->inRandomOrder()->value('id') ?? 1,
                'expense_date' => now()->subDays(rand(1, 30)),
                'amount' => rand(1, 9),
                'description' => $faker->sentence,
                'status' => 'active',
            ]);
        }
    }
}
