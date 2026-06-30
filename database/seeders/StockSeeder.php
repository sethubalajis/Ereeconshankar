<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
class StockSeeder extends Seeder {
    public function run(): void {
        $faker = Faker::create('en_IN');
        DB::table('stocks')->truncate();
        for ($i = 1; $i <= 15; $i++) {
            DB::table('stocks')->insert([
                'product_id' => $i,
                'branch_id' => $faker->numberBetween(1, 3),
                'warehouse_id' => $faker->numberBetween(1, 2),
                'quantity' => $faker->numberBetween(50, 500),
                'avg_cost' => $faker->randomFloat(2, 200, 3000),
            ]);
        }
    }
}
