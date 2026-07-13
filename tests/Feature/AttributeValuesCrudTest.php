<?php

use App\Filament\Resources\AttributeValues\Pages\CreateAttributeValue;
use App\Filament\Resources\AttributeValues\Pages\EditAttributeValue;
use App\Filament\Resources\AttributeValues\Pages\ListAttributeValues;
use App\Models\AttributeValue;
use App\Models\ProductAttribute;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeAttributeValueCrudAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

it('performs attribute value crud operations', function (): void {
    actingAs(makeAttributeValueCrudAdminUser());

    $attribute = ProductAttribute::query()->create([
        'name' => 'Material',
        'code' => 'PAT010',
        'type' => 'text',
    ]);

    Livewire::test(CreateAttributeValue::class)
        ->fillForm([
            'product_attribute_id' => $attribute->getKey(),
            'value' => 'Cotton',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = AttributeValue::query()->where('value', 'Cotton')->firstOrFail();

    Livewire::test(ListAttributeValues::class)
        ->assertSuccessful()
        ->assertSee($record->value);

    Livewire::test(EditAttributeValue::class, ['record' => $record->getKey()])
        ->fillForm([
            'product_attribute_id' => $attribute->getKey(),
            'value' => 'Linen',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->value)->toBe('Linen');

    Livewire::test(EditAttributeValue::class, ['record' => $record->getKey()])
        ->callAction('delete');

    expect(AttributeValue::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
