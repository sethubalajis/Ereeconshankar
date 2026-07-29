<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('role_permissions')->insertOrIgnore([
                'role_id' => DB::table('roles')->inRandomOrder()->value('id') ?? 1,
                'permission_id' => DB::table('permissions')->inRandomOrder()->value('id') ?? 1,
            ]);
        }
    }
}
