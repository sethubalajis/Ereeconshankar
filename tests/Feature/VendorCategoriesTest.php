<?php

use App\Filament\Resources\VendorCategories\Pages\CreateVendorCategory;
use App\Filament\Resources\VendorCategories\Pages\EditVendorCategory;
use App\Filament\Resources\VendorCategories\Pages\ListVendorCategories;
use App\Models\Role;
use App\Models\User;
use App\Models\VendorCategory;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeVendorCategoriesAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

it('redirects guests away from vendor categories list', function (): void {
    get('/admin/vendor-categories')->assertRedirect('/admin/login');
});

it('redirects guests away from create vendor category page', function (): void {
    get('/admin/vendor-categories/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a vendor category', function (): void {
    actingAs(makeVendorCategoriesAdminUser());

    Livewire::test(CreateVendorCategory::class)
        ->fillForm(['name' => '', 'code' => ''])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required', 'code' => 'required']);
});

it('performs vendor category crud operations', function (): void {
    actingAs(makeVendorCategoriesAdminUser());

    Livewire::test(CreateVendorCategory::class)
        ->fillForm(['name' => 'Raw Materials', 'code' => 'RAW', 'description' => 'Raw suppliers'])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = VendorCategory::query()->firstOrFail();
    Livewire::test(ListVendorCategories::class)->assertSuccessful()->assertSee('Raw Materials');

    Livewire::test(EditVendorCategory::class, ['record' => $record->getKey()])
        ->fillForm(['name' => 'Packaging', 'code' => 'PACK', 'description' => 'Packaging suppliers'])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->code)->toBe('PACK');

    Livewire::test(EditVendorCategory::class, ['record' => $record->getKey()])->callAction('delete');
    expect(VendorCategory::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
