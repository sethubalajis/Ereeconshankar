<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class GstRateSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('gst_rates')->insert([
                'cgst_percentage' => rand(1, 9),
                'sgst_percentage' => rand(1, 9),
                'igst_percentage' => rand(1, 9),
                'cess_percentage' => rand(1, 9),
                'status' => true,
            ]);
        }
    }
}
