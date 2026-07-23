<?php

use App\Models\Role;
use App\Models\User;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeTdsRecordsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

it('redirects guests away from tds records list', function (): void {
    get('/admin/tds-records')->assertRedirect('/admin/login');
});

it('redirects guests away from create tds record page', function (): void {
    get('/admin/tds-records/create')->assertRedirect('/admin/login');
});

