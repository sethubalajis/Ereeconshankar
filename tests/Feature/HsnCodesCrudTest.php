<?php

use App\Filament\Resources\HsnCodes\Pages\CreateHsnCode;
use App\Filament\Resources\HsnCodes\Pages\EditHsnCode;
use App\Filament\Resources\HsnCodes\Pages\ListHsnCodes;
use App\Models\HsnCode;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeHsnCodeCrudAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

it('performs hsn code crud operations', function (): void {
    actingAs(makeHsnCodeCrudAdminUser());

    Livewire::test(CreateHsnCode::class)
        ->fillForm([
            'hsn_code' => 'HSN1001',
            'description' => 'HSN CRUD test',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = HsnCode::query()->where('hsn_code', 'HSN1001')->firstOrFail();

    Livewire::test(ListHsnCodes::class)
        ->assertSuccessful()
        ->assertSee($record->hsn_code);

    Livewire::test(EditHsnCode::class, ['record' => $record->getKey()])
        ->fillForm([
            'hsn_code' => 'HSN1002',
            'description' => 'HSN CRUD updated',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->hsn_code)->toBe('HSN1002');

    Livewire::test(EditHsnCode::class, ['record' => $record->getKey()])
        ->callAction('delete');

    expect(HsnCode::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
