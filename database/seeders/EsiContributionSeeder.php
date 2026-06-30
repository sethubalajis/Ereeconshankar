<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class EsiContributionSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('esi_contributions')->insert([
                'employee_id' => DB::table('employees')->inRandomOrder()->value('id') ?? 1,
                'employee_share' => rand(1, 9),
                'employer_share' => rand(1, 9),
                'month' => now()->subDays(rand(1, 30)),
            ]);
        }
    }
}
