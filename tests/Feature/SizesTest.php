<?php

use App\Filament\Resources\Sizes\Pages\CreateSize;
use App\Filament\Resources\Sizes\Pages\EditSize;
use App\Filament\Resources\Sizes\Pages\ListSizes;
use App\Models\Role;
use App\Models\Size;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeSizesAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

it('redirects guests away from sizes list', function (): void {
    get('/admin/sizes')->assertRedirect('/admin/login');
});

it('redirects guests away from create size page', function (): void {
    get('/admin/sizes/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a size', function (): void {
    actingAs(makeSizesAdminUser());

    Livewire::test(CreateSize::class)
        ->fillForm([
            'name' => '',
            'code' => '',
            'status' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'required',
            'code' => 'required',
            'status' => 'required',
        ]);
});

it('performs size crud operations', function (): void {
    actingAs(makeSizesAdminUser());

    Livewire::test(CreateSize::class)
        ->fillForm([
            'name' => 'Large',
            'code' => 'L',
            'status' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = Size::query()->firstOrFail();

    Livewire::test(ListSizes::class)
        ->assertSuccessful()
        ->assertSee('Large');

    Livewire::test(EditSize::class, ['record' => $record->getKey()])
        ->fillForm([
            'name' => 'Extra Large',
            'code' => 'XL',
            'status' => false,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->code)->toBe('XL');

    Livewire::test(EditSize::class, ['record' => $record->getKey()])->callAction('delete');
    expect(Size::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
