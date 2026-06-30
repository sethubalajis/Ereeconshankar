<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('attendances')->insert([
                'employee_id' => DB::table('employees')->inRandomOrder()->value('id') ?? 1,
                'date' => now()->subDays(rand(1, 30)),
                'check_in' => now()->format('H:i:s'),
                'check_out' => now()->format('H:i:s'),
                'status' => 'active',
            ]);
        }
    }
}
