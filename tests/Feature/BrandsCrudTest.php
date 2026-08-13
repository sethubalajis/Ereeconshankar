<?php

use App\Filament\Resources\Brands\Pages\CreateBrand;
use App\Filament\Resources\Brands\Pages\EditBrand;
use App\Filament\Resources\Brands\Pages\ListBrands;
use App\Models\Brand;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeBrandCrudAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

it('performs brand crud operations', function (): void {
    actingAs(makeBrandCrudAdminUser());

    Livewire::test(CreateBrand::class)
        ->fillForm([
            'name' => 'Brand CRUD',
            'description' => 'Brand CRUD test',
            'status' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = Brand::query()->where('name', 'Brand CRUD')->firstOrFail();

    Livewire::test(ListBrands::class)
        ->assertSuccessful()
        ->assertSee($record->name);

    Livewire::test(EditBrand::class, ['record' => $record->getKey()])
        ->fillForm([
            'name' => 'Brand CRUD Updated',
            'description' => 'Brand CRUD updated',
            'status' => true,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->name)->toBe('Brand CRUD Updated');

    Livewire::test(EditBrand::class, ['record' => $record->getKey()])
        ->callAction('delete');

    expect(Brand::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
