<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
class PosSaleSeeder extends Seeder {
    public function run(): void {
        $faker = Faker::create('en_IN');
        DB::table('pos_sales')->truncate();
        for ($i = 1; $i <= 10; $i++) {
            DB::table('pos_sales')->insert([
                'branch_id' => $faker->numberBetween(1, 3),
                'pos_terminal_id' => 1,
                'customer_id' => $faker->numberBetween(1, 10),
                'invoice_number' => 'INV' . date('Y') . str_pad($i, 4, '0', STR_PAD_LEFT),
                'sale_date' => $faker->date(),
                'total_amount' => $faker->randomFloat(2, 1000, 20000),
                'net_amount' => $faker->randomFloat(2, 1000, 20000),
                'payment_mode_id' => 1,
                'status' => 'completed',
            ]);
        }
    }
}
