<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
class PaymentSeeder extends Seeder {
    public function run(): void {
        $faker = Faker::create('en_IN');
        DB::table('payments')->truncate();
        for ($i = 1; $i <= 10; $i++) {
            DB::table('payments')->insert([
                'vendor_id' => $faker->numberBetween(1, 10),
                'customer_id' => $faker->numberBetween(1, 10),
                'payment_date' => $faker->date(),
                'amount' => $faker->randomFloat(2, 1000, 20000),
                'payment_mode_id' => 1,
                'reference_no' => 'UPI' . $faker->numerify('##########'),
                'status' => 'completed',
            ]);
        }
    }
}
