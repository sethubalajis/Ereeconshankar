<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class AttributeValueSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('attribute_values')->insert([
                'product_attribute_id' => DB::table('product_attributes')->inRandomOrder()->value('id') ?? 1,
                'value' => $faker->word . ' ' . $faker->word,
            ]);
        }
    }
}
