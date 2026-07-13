<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\PermissionRegistrar;

class ProductMasterPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $resources = [
            'brands',
            'hsn-codes',
            'attribute-values',
            'tax-groups',
            'product-variants',
            'product-units',
            'product-sub-categories',
            'products',
            'product-images',
            'product-categories',
            'product-barcodes',
            'product-attributes',
        ];

        $actions = ['view', 'create', 'update', 'delete'];

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
                    'module' => 'product-master',
                    'description' => 'Product Master policy permission',
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
                'module' => 'product-master',
                'description' => 'Product Master policy permission',
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
