<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\PermissionRegistrar;

class OrganizationManagementPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $resources = [
            'companies',
            'business-units',
            'cost-centers',
            'financial-years',
            'currencies',
            'countries',
            'states',
            'cities',
        ];

        $actions = ['viewAny', 'view', 'create', 'update', 'delete', 'deleteAny'];

        $permissionNames = [];

        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                $permissionNames[] = "{$resource}.{$action}";
            }
        }

        $hasGuardName = Schema::hasColumn('permissions', 'guard_name');
        $permissionIds = [];

        foreach ($permissionNames as $name) {
            if ($hasGuardName) {
                $permission = Permission::query()->firstOrCreate([
                    'name' => $name,
                    'guard_name' => 'web',
                ], [
                    'module' => 'organization-management',
                    'description' => 'Organization Management policy permission',
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
                'module' => 'organization-management',
                'description' => 'Organization Management policy permission',
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
