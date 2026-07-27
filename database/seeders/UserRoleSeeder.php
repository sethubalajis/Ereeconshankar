<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('user_roles')->insert([
                'user_id' => DB::table('users')->inRandomOrder()->value('id') ?? 1,
                'role_id' => DB::table('roles')->inRandomOrder()->value('id') ?? 1,
                'model_type' => 'App\Models\User',
            ]);
        }
    }
}
