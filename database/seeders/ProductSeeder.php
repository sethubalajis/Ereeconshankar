<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
class ProductSeeder extends Seeder {
    public function run(): void {
        $faker = Faker::create('en_IN');
        DB::table('products')->truncate();
        for ($i = 1; $i <= 15; $i++) {
            DB::table('products')->insert([
                'name' => 'Premium ' . $faker->word . ' ' . $faker->word,
                'code' => 'PRD' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'brand_id' => $faker->numberBetween(1, 10),
                'product_category_id' => $faker->numberBetween(1, 14),
                'hsn_code_id' => 1,
                'base_price' => $faker->randomFloat(2, 500, 5000),
                'is_active' => true,
            ]);
        }
    }
}
