<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class VendorAgreementSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('vendor_agreements')->insert([
                'vendor_id' => DB::table('vendors')->inRandomOrder()->value('id') ?? 1,
                'title' => $faker->word . ' ' . $faker->word,
                'start_date' => now()->subDays(rand(1, 30)),
                'end_date' => now()->subDays(rand(1, 30)),
                'document_path' => $faker->word . ' ' . $faker->word,
                'status' => 'active',
            ]);
        }
    }
}
