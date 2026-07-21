<?php

use App\Filament\Resources\Permissions\Pages\CreatePermission;
use App\Filament\Resources\Permissions\Pages\EditPermission;
use App\Filament\Resources\Permissions\Pages\ListPermissions;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makePermissionsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

it('redirects guests away from permissions list', function (): void { get('/admin/permissions')->assertRedirect('/admin/login'); });
it('redirects guests away from create permission page', function (): void { get('/admin/permissions/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a permission', function (): void {
    actingAs(makePermissionsAdminUser());

    $data = ['name' => ''];
    if (! Schema::hasColumn('permissions', 'module') && Schema::hasColumn('permissions', 'guard_name')) {
        $data['guard_name'] = '';
    }

    $errors = ['name' => 'required'];
    if (! Schema::hasColumn('permissions', 'module') && Schema::hasColumn('permissions', 'guard_name')) {
        $errors['guard_name'] = 'required';
    }

    Livewire::test(CreatePermission::class)
        ->fillForm($data)
        ->call('create')
        ->assertHasFormErrors($errors);
});

it('performs permission crud operations', function (): void {
    actingAs(makePermissionsAdminUser());

    $create = ['name' => 'view reports'];
    if (! Schema::hasColumn('permissions', 'module') && Schema::hasColumn('permissions', 'guard_name')) {
        $create['guard_name'] = 'web';
    }
    if (Schema::hasColumn('permissions', 'module')) {
        $create['module'] = 'Reports';
    }
    if (Schema::hasColumn('permissions', 'description')) {
        $create['description'] = 'Can view reports';
    }

    Livewire::test(CreatePermission::class)
        ->fillForm($create)
        ->call('create')
        ->assertHasNoFormErrors();

    $record = Permission::query()->where('name', 'view reports')->firstOrFail();
    Livewire::test(ListPermissions::class)->assertSuccessful()->assertSee('view reports');

    $edit = ['name' => 'edit reports'];
    if (! Schema::hasColumn('permissions', 'module') && Schema::hasColumn('permissions', 'guard_name')) {
        $edit['guard_name'] = 'web';
    }
    if (Schema::hasColumn('permissions', 'module')) {
        $edit['module'] = 'Reports';
    }
    if (Schema::hasColumn('permissions', 'description')) {
        $edit['description'] = 'Can edit reports';
    }

    Livewire::test(EditPermission::class, ['record' => $record->getKey()])
        ->fillForm($edit)
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->name)->toBe('edit reports');

    Livewire::test(EditPermission::class, ['record' => $record->getKey()])->callAction('delete');
    expect(Permission::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
