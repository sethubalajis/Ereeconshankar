<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class AssetDisposalSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('asset_disposals')->insert([
                'asset_id' => DB::table('assets')->inRandomOrder()->value('id') ?? 1,
                'disposal_date' => now()->subDays(rand(1, 30)),
                'sale_value' => rand(1, 9),
                'reason' => $faker->sentence,
            ]);
        }
    }
}
