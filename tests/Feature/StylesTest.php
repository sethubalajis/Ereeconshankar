<?php

use App\Filament\Resources\Styles\Pages\CreateStyle;
use App\Filament\Resources\Styles\Pages\EditStyle;
use App\Filament\Resources\Styles\Pages\ListStyles;
use App\Models\Role;
use App\Models\Style;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeStylesAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

it('redirects guests away from styles list', function (): void {
    get('/admin/styles')->assertRedirect('/admin/login');
});

it('redirects guests away from create style page', function (): void {
    get('/admin/styles/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a style', function (): void {
    actingAs(makeStylesAdminUser());

    Livewire::test(CreateStyle::class)
        ->fillForm(['name' => '', 'code' => '', 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required', 'code' => 'required', 'status' => 'required']);
});

it('performs style crud operations', function (): void {
    actingAs(makeStylesAdminUser());

    Livewire::test(CreateStyle::class)
        ->fillForm(['name' => 'Casual', 'code' => 'CAS', 'description' => 'Casual wear', 'status' => true])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = Style::query()->firstOrFail();
    Livewire::test(ListStyles::class)->assertSuccessful()->assertSee('Casual');

    Livewire::test(EditStyle::class, ['record' => $record->getKey()])
        ->fillForm(['name' => 'Formal', 'code' => 'FOR', 'description' => 'Formal wear', 'status' => false])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->code)->toBe('FOR');

    Livewire::test(EditStyle::class, ['record' => $record->getKey()])->callAction('delete');
    expect(Style::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
