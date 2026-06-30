<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
class EmployeeSeeder extends Seeder {
    public function run(): void {
        $faker = Faker::create('en_IN');
        DB::table('employees')->truncate();
        for ($i = 1; $i <= 10; $i++) {
            DB::table('employees')->insert([
                'user_id' => $i,
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'employee_code' => 'EMP' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'branch_id' => $faker->numberBetween(1, 3),
                'department_id' => $faker->numberBetween(1, 10),
                'designation_id' => 1,
                'joining_date' => $faker->date(),
                'status' => 'active',
            ]);
        }
    }
}
