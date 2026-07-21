<?php

use App\Filament\Resources\Collections\Pages\CreateCollection;
use App\Filament\Resources\Collections\Pages\EditCollection;
use App\Filament\Resources\Collections\Pages\ListCollections;
use App\Models\Collection;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeCollectionsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

it('redirects guests away from collections list', function (): void { get('/admin/collections')->assertRedirect('/admin/login'); });
it('redirects guests away from create collection page', function (): void { get('/admin/collections/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a collection', function (): void {
    actingAs(makeCollectionsAdminUser());
    Livewire::test(CreateCollection::class)
        ->fillForm(['name' => '', 'code' => '', 'description' => '', 'start_date' => null, 'end_date' => null, 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required', 'code' => 'required', 'status' => 'required']);
});

it('performs collection crud operations', function (): void {
    actingAs(makeCollectionsAdminUser());

    Livewire::test(CreateCollection::class)
        ->fillForm(['name' => 'Summer Collection', 'code' => 'COL001', 'description' => 'Seasonal items', 'start_date' => '2026-07-21', 'end_date' => '2026-08-21', 'status' => true])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = Collection::query()->firstOrFail();
    Livewire::test(ListCollections::class)->assertSuccessful()->assertSee('Summer Collection');

    Livewire::test(EditCollection::class, ['record' => $record->getKey()])
        ->fillForm(['name' => 'Winter Collection', 'code' => 'COL002', 'description' => 'Cold season items', 'start_date' => '2026-09-01', 'end_date' => '2026-10-01', 'status' => false])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->name)->toBe('Winter Collection');

    Livewire::test(EditCollection::class, ['record' => $record->getKey()])->callAction('delete');
    expect(Collection::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
