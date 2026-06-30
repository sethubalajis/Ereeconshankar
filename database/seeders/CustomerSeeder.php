<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
class CustomerSeeder extends Seeder {
    public function run(): void {
        $faker = Faker::create('en_IN');
        DB::table('customers')->truncate();
        for ($i = 1; $i <= 10; $i++) {
            DB::table('customers')->insert([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->numerify('9#########'),
                'address' => $faker->address,
                'status' => true,
            ]);
        }
    }
}
