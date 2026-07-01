<?php

use App\Filament\Resources\ProductAttributes\Pages\CreateProductAttribute;
use App\Filament\Resources\ProductAttributes\Pages\EditProductAttribute;
use App\Filament\Resources\ProductAttributes\Pages\ListProductAttributes;
use App\Models\ProductAttribute;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeProductAttributeCrudAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

it('performs product attribute crud operations', function (): void {
    actingAs(makeProductAttributeCrudAdminUser());

    Livewire::test(CreateProductAttribute::class)
        ->fillForm([
            'name' => 'Size',
            'code' => 'PAT001',
            'type' => 'text',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = ProductAttribute::query()->where('code', 'PAT001')->firstOrFail();

    Livewire::test(ListProductAttributes::class)
        ->assertSuccessful()
        ->assertSee($record->name);

    Livewire::test(EditProductAttribute::class, ['record' => $record->getKey()])
        ->fillForm([
            'name' => 'Color',
            'code' => 'PAT002',
            'type' => 'text',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->code)->toBe('PAT002');

    Livewire::test(EditProductAttribute::class, ['record' => $record->getKey()])
        ->callAction('delete');

    expect(ProductAttribute::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
