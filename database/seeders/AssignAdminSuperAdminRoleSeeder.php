<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssignAdminSuperAdminRoleSeeder extends Seeder
{
    public function run(): void
    {
        $user = DB::table('users')->where('email', 'admin@example.com')->first();
        $role = DB::table('roles')->where('name', 'Super Admin')->first();

        if (! $user || ! $role) {
            return;
        }

        $exists = DB::table('user_roles')
            ->where('user_id', $user->id)
            ->where('role_id', $role->id)
            ->exists();

        if (! $exists) {
            DB::table('user_roles')->insert([
                'user_id' => $user->id,
                'role_id' => $role->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
