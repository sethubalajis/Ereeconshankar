<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Spatie\Permission\PermissionRegistrar;

class AllMenusPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $resourceDirectories = [
            app_path('Filament/Resources'),
            app_path('Filament/Branch/Resources'),
        ];

        $slugs = [];

        foreach ($resourceDirectories as $dir) {
            if (!is_dir($dir)) continue;
            $folders = scandir($dir);
            foreach ($folders as $folder) {
                if ($folder === '.' || $folder === '..') continue;
                $slugKebab = Str::kebab($folder);
                $slugs[$slugKebab] = $folder;
            }
        }

        $modelFiles = glob(app_path('Models/*.php'));
        foreach ($modelFiles as $file) {
            $modelName = basename($file, '.php');
            $pluralKebab = Str::kebab(Str::plural($modelName));
            $slugs[$pluralKebab] = $modelName;
        }

        // Actions requested: ONLY view, create, update, delete (viewAny and deleteAny explicitly excluded)
        $actions = ['view', 'create', 'update', 'delete'];

        $hasGuardName = Schema::hasColumn('permissions', 'guard_name');
        $hasModule = Schema::hasColumn('permissions', 'module');

        $permissionIds = [];
        $uniqueResources = array_keys($slugs);
        sort($uniqueResources);

        foreach ($uniqueResources as $resource) {
            foreach ($actions as $action) {
                $permName = "{$resource}.{$action}";

                $existing = Permission::where('name', $permName)->first();

                if ($existing) {
                    $permissionIds[] = $existing->id;
                } else {
                    $data = ['name' => $permName];
                    if ($hasGuardName) {
                        $data['guard_name'] = 'web';
                    }
                    if ($hasModule) {
                        $data['module'] = $resource;
                    }
                    $data['created_at'] = now();
                    $data['updated_at'] = now();

                    $permId = DB::table('permissions')->insertGetId($data);
                    $permissionIds[] = $permId;
                }
            }
        }

        $permissionIds = array_unique($permissionIds);

        $roleIds = Role::whereIn('name', ['Admin', 'Super Admin', 'admin', 'super-admin'])->pluck('id')->all();

        if (empty($roleIds)) {
            $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
            $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
            $roleIds = [$adminRole->id, $superAdminRole->id];
        }

        foreach ($roleIds as $roleId) {
            foreach ($permissionIds as $permId) {
                if (Schema::hasTable('role_permissions')) {
                    $exists = DB::table('role_permissions')
                        ->where('role_id', $roleId)
                        ->where('permission_id', $permId)
                        ->exists();

                    if (!$exists) {
                        $insertData = [
                            'role_id' => $roleId,
                            'permission_id' => $permId,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                        if (Schema::hasColumn('role_permissions', 'branch_id')) {
                            $insertData['branch_id'] = null;
                        }
                        DB::table('role_permissions')->insert($insertData);
                    }
                }

                if (Schema::hasTable('role_has_permissions')) {
                    $existsSpatie = DB::table('role_has_permissions')
                        ->where('role_id', $roleId)
                        ->where('permission_id', $permId)
                        ->exists();

                    if (!$existsSpatie) {
                        DB::table('role_has_permissions')->insert([
                            'role_id' => $roleId,
                            'permission_id' => $permId,
                        ]);
                    }
                }
            }
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
