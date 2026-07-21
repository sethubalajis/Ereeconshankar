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
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeAttributeValuesAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createProductAttributeForValues(): ProductAttribute
{
    return ProductAttribute::query()->create(['name' => 'Size', 'code' => 'PA-SIZE', 'type' => 'select']);
}

it('redirects guests away from attribute values list', function (): void {
    get('/admin/attribute-values')->assertRedirect('/admin/login');
});

it('redirects guests away from create attribute value page', function (): void {
    get('/admin/attribute-values/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating an attribute value', function (): void {
    actingAs(makeAttributeValuesAdminUser());

    Livewire::test(CreateAttributeValue::class)
        ->fillForm(['product_attribute_id' => null, 'value' => ''])
        ->call('create')
        ->assertHasFormErrors(['product_attribute_id' => 'required', 'value' => 'required']);
});

it('performs attribute value crud operations', function (): void {
    actingAs(makeAttributeValuesAdminUser());
    $attribute = createProductAttributeForValues();

    Livewire::test(CreateAttributeValue::class)
        ->fillForm(['product_attribute_id' => $attribute->getKey(), 'value' => 'Large'])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = AttributeValue::query()->firstOrFail();
    Livewire::test(ListAttributeValues::class)->assertSuccessful()->assertSee('Large');

    Livewire::test(EditAttributeValue::class, ['record' => $record->getKey()])
        ->fillForm(['product_attribute_id' => $attribute->getKey(), 'value' => 'XL'])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->value)->toBe('XL');

    Livewire::test(EditAttributeValue::class, ['record' => $record->getKey()])->callAction('delete');
    expect(AttributeValue::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
