<?php

use App\Filament\Resources\Users\Pages\UserPermissions;
use App\Models\Permission;
use App\Models\User;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

it('saves direct user permissions from the checkbox matrix', function (): void {
    $user = User::factory()->create();
    Permission::query()->create(['name' => 'brands.view', 'guard_name' => 'web']);

    $page = new UserPermissions();
    $page->record = $user;
    $page->availablePermissionsByAction = ['brands' => ['view' => ['brands.view']]];
    $page->matrix = ['brands' => ['view' => true]];
    $page->save();

    $directPermissions = $user->fresh()->getDirectPermissions()->pluck('name')->all();

    expect($directPermissions)->toContain('brands.view');
});

it('shows inherited tick marks for a branch even when a direct permission already exists', function (): void {
    $page = new UserPermissions();
    $page->inheritedPermissionsByAction = ['brands' => ['view' => true]];
    $page->directPermissionsByAction = ['brands' => ['view' => true]];

    expect($page->isInheritedAction('brands', 'view'))->toBeTrue();
});
