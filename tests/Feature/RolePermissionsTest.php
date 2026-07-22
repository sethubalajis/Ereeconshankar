<?php

use App\Filament\Resources\Roles\Pages\RolePermissions;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\User;
use Livewire\Livewire;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

it('refreshes the permission matrix when the selected branch changes', function (): void {
    $company = Company::query()->create(['name' => 'Role Perm Co', 'code' => 'RPC1', 'status' => true]);
    $branch = Branch::query()->create([
        'company_id' => $company->getKey(),
        'name' => 'Role Perm Branch',
        'code' => 'RPB1',
        'status' => true,
    ]);

    $role = Role::query()->create(['name' => 'Branch Manager', 'guard_name' => 'web']);

    Livewire::test(RolePermissions::class, ['record' => $role])
        ->set('selectedBranchId', $branch->getKey())
        ->assertDispatched('$refresh');
});

it('stores permissions separately for each selected branch', function (): void {
    $company = Company::query()->create(['name' => 'Role Perm Co', 'code' => 'RPC1', 'status' => true]);
    $branchA = Branch::query()->create([
        'company_id' => $company->getKey(),
        'name' => 'Branch A',
        'code' => 'BRA1',
        'status' => true,
    ]);
    $branchB = Branch::query()->create([
        'company_id' => $company->getKey(),
        'name' => 'Branch B',
        'code' => 'BRB1',
        'status' => true,
    ]);

    $role = Role::query()->create(['name' => 'Branch Manager', 'guard_name' => 'web']);
    $permissionA = Permission::query()->create(['name' => 'brands.view', 'guard_name' => 'web']);
    $permissionB = Permission::query()->create(['name' => 'products.view', 'guard_name' => 'web']);

    Livewire::test(RolePermissions::class, ['record' => $role])
        ->set('selectedBranchId', $branchA->getKey())
        ->set('matrix', ['brands' => ['view' => true], 'products' => ['view' => false]])
        ->call('save');

    Livewire::test(RolePermissions::class, ['record' => $role])
        ->set('selectedBranchId', $branchB->getKey())
        ->set('matrix', ['brands' => ['view' => false], 'products' => ['view' => true]])
        ->call('save');

    $branchAPermissions = RolePermission::query()
        ->where('role_id', $role->getKey())
        ->where('branch_id', $branchA->getKey())
        ->pluck('permission_id')
        ->all();
    $branchBPermissions = RolePermission::query()
        ->where('role_id', $role->getKey())
        ->where('branch_id', $branchB->getKey())
        ->pluck('permission_id')
        ->all();

    expect($branchAPermissions)->toContain($permissionA->getKey());
    expect($branchAPermissions)->not->toContain($permissionB->getKey());
    expect($branchBPermissions)->toContain($permissionB->getKey());
    expect($branchBPermissions)->not->toContain($permissionA->getKey());
});

it('grants branch access to users assigned to the role when saving permissions', function (): void {
    $company = Company::query()->create(['name' => 'Role Perm Co', 'code' => 'RPC1', 'status' => true]);
    $branch = Branch::query()->create([
        'company_id' => $company->getKey(),
        'name' => 'Role Perm Branch',
        'code' => 'RPB1',
        'status' => true,
    ]);

    $role = Role::query()->create(['name' => 'Branch Manager', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    Livewire::test(RolePermissions::class, ['record' => $role])
        ->set('selectedBranchId', $branch->getKey())
        ->call('save');

    expect($user->fresh()->hasBranchAccessTo($branch->getKey()))->toBeTrue();
});

it('skips permission names that are not available for the role guard', function (): void {
    $role = Role::query()->create(['name' => 'Guarded Role', 'guard_name' => 'web']);
    Permission::query()->create(['name' => 'brands.view', 'guard_name' => 'api']);

    $page = new RolePermissions();
    $page->record = $role;
    $page->matrix = ['brands' => ['view' => true]];
    $page->availablePermissionsByAction = ['brands' => ['view' => ['brands.view']]];

    expect(fn () => $page->save())->not->toThrow(Exception::class);
});
