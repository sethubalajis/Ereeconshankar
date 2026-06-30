<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
class AssetSeeder extends Seeder {
    public function run(): void {
        $faker = Faker::create('en_IN');
        DB::table('assets')->truncate();
        for ($i = 1; $i <= 10; $i++) {
            DB::table('assets')->insert([
                'name' => 'Asset ' . $faker->word,
                'asset_code' => 'AST' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'asset_category_id' => 1,
                'branch_id' => $faker->numberBetween(1, 3),
                'purchase_date' => $faker->date(),
                'purchase_cost' => $faker->randomFloat(2, 10000, 100000),
                'status' => 'active',
            ]);
        }
    }
}
