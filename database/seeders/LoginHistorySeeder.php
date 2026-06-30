<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class LoginHistorySeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('login_histories')->insert([
                'user_id' => DB::table('users')->inRandomOrder()->value('id') ?? 1,
                'ip_address' => $faker->word . ' ' . $faker->word,
                'user_agent' => $faker->sentence,
                'login_at' => now(),
                'logout_at' => now(),
            ]);
        }
    }
}
