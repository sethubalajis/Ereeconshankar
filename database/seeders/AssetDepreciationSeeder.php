<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class AssetDepreciationSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('asset_depreciations')->insert([
                'asset_id' => DB::table('assets')->inRandomOrder()->value('id') ?? 1,
                'depreciation_date' => now()->subDays(rand(1, 30)),
                'amount' => rand(1, 9),
                'current_value' => rand(1, 9),
            ]);
        }
    }
}
