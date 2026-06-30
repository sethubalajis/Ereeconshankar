<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
class PurchaseOrderSeeder extends Seeder {
    public function run(): void {
        $faker = Faker::create('en_IN');
        DB::table('purchase_orders')->truncate();
        for ($i = 1; $i <= 10; $i++) {
            DB::table('purchase_orders')->insert([
                'vendor_id' => $faker->numberBetween(1, 10),
                'branch_id' => $faker->numberBetween(1, 3),
                'po_number' => 'PO' . date('Y') . str_pad($i, 4, '0', STR_PAD_LEFT),
                'po_date' => $faker->date(),
                'expected_delivery_date' => $faker->date(),
                'status' => 'approved',
                'total_amount' => $faker->randomFloat(2, 5000, 50000),
            ]);
        }
    }
}
