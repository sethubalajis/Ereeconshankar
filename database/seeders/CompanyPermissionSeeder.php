<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\PermissionRegistrar;

class CompanyPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'companies.viewAny',
            'companies.view',
            'companies.create',
            'companies.update',
            'companies.delete',
            'companies.deleteAny',
        ];

        $hasGuardName = Schema::hasColumn('permissions', 'guard_name');
        $permissionIds = [];

        foreach ($permissions as $name) {
            if ($hasGuardName) {
                $permission = Permission::query()->firstOrCreate([
                    'name' => $name,
                    'guard_name' => 'web',
                ]);

                $permissionIds[] = $permission->id;
                continue;
            }

            $existing = DB::table('permissions')->where('name', $name)->first();

            if ($existing) {
                $permissionIds[] = $existing->id;
                continue;
            }

            $permissionIds[] = DB::table('permissions')->insertGetId([
                'name' => $name,
                'guard_name' => 'web',
                'module' => 'companies',
                'description' => 'Companies access permission',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $roleIds = Role::query()
            ->whereIn('name', ['Admin', 'Super Admin'])
            ->pluck('id')
            ->all();

        foreach ($roleIds as $roleId) {
            foreach ($permissionIds as $permissionId) {
                $exists = DB::table('role_permissions')
                    ->where('role_id', $roleId)
                    ->where('permission_id', $permissionId)
                    ->exists();

                if (! $exists) {
                    DB::table('role_permissions')->insert([
                        'role_id' => $roleId,
                        'permission_id' => $permissionId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
