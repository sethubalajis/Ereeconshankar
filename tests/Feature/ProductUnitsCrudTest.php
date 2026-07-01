<?php

use App\Filament\Resources\ProductUnits\Pages\CreateProductUnit;
use App\Filament\Resources\ProductUnits\Pages\EditProductUnit;
use App\Filament\Resources\ProductUnits\Pages\ListProductUnits;
use App\Models\ProductUnit;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeProductUnitCrudAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

it('performs product unit crud operations', function (): void {
    actingAs(makeProductUnitCrudAdminUser());

    Livewire::test(CreateProductUnit::class)
        ->fillForm([
            'name' => 'Piece',
            'code' => 'UNT001',
            'description' => 'Unit CRUD test',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = ProductUnit::query()->where('code', 'UNT001')->firstOrFail();

    Livewire::test(ListProductUnits::class)
        ->assertSuccessful()
        ->assertSee($record->name);

    Livewire::test(EditProductUnit::class, ['record' => $record->getKey()])
        ->fillForm([
            'name' => 'Box',
            'code' => 'UNT002',
            'description' => 'Unit CRUD updated',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->code)->toBe('UNT002');

    Livewire::test(EditProductUnit::class, ['record' => $record->getKey()])
        ->callAction('delete');

    expect(ProductUnit::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
