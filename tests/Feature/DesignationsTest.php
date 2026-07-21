<?php

use App\Filament\Resources\Designations\Pages\CreateDesignation;
use App\Filament\Resources\Designations\Pages\EditDesignation;
use App\Filament\Resources\Designations\Pages\ListDesignations;
use App\Models\Designation;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeDesignationsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

it('redirects guests away from designations list', function (): void { get('/admin/designations')->assertRedirect('/admin/login'); });
it('redirects guests away from create designation page', function (): void { get('/admin/designations/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a designation', function (): void {
    actingAs(makeDesignationsAdminUser());
    Livewire::test(CreateDesignation::class)
        ->fillForm(['name' => '', 'description' => '', 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required', 'status' => 'required']);
});

it('performs designation crud operations', function (): void {
    actingAs(makeDesignationsAdminUser());

    Livewire::test(CreateDesignation::class)
        ->fillForm(['name' => 'Manager', 'description' => 'Team manager', 'status' => true])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = Designation::query()->firstOrFail();
    Livewire::test(ListDesignations::class)->assertSuccessful()->assertSee('Manager');

    Livewire::test(EditDesignation::class, ['record' => $record->getKey()])
        ->fillForm(['name' => 'Senior Manager', 'description' => 'Senior role', 'status' => false])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->name)->toBe('Senior Manager');

    Livewire::test(EditDesignation::class, ['record' => $record->getKey()])->callAction('delete');
    expect(Designation::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
