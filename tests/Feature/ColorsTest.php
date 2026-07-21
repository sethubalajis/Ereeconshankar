<?php

use App\Filament\Resources\Colors\Pages\CreateColor;
use App\Filament\Resources\Colors\Pages\EditColor;
use App\Filament\Resources\Colors\Pages\ListColors;
use App\Models\Color;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeColorsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

it('redirects guests away from colors list', function (): void { get('/admin/colors')->assertRedirect('/admin/login'); });
it('redirects guests away from create color page', function (): void { get('/admin/colors/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a color', function (): void {
    actingAs(makeColorsAdminUser());
    Livewire::test(CreateColor::class)
        ->fillForm(['name' => '', 'hex_code' => '', 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required', 'status' => 'required']);
});

it('performs color crud operations', function (): void {
    actingAs(makeColorsAdminUser());

    Livewire::test(CreateColor::class)
        ->fillForm(['name' => 'Red', 'hex_code' => '#FF0000', 'status' => true])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = Color::query()->firstOrFail();
    Livewire::test(ListColors::class)->assertSuccessful()->assertSee('Red');

    Livewire::test(EditColor::class, ['record' => $record->getKey()])
        ->fillForm(['name' => 'Blue', 'hex_code' => '#0000FF', 'status' => false])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->name)->toBe('Blue');

    Livewire::test(EditColor::class, ['record' => $record->getKey()])->callAction('delete');
    expect(Color::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
