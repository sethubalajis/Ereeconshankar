<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class VendorContactSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('vendor_contacts')->insert([
                'vendor_id' => DB::table('vendors')->inRandomOrder()->value('id') ?? 1,
                'name' => $faker->word . ' ' . $faker->word,
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->numerify('##########'),
                'designation' => $faker->word . ' ' . $faker->word,
                'is_primary' => true,
            ]);
        }
    }
}
