<?php

use App\Filament\Resources\AssetCategories\Pages\CreateAssetCategory;
use App\Filament\Resources\AssetCategories\Pages\EditAssetCategory;
use App\Filament\Resources\AssetCategories\Pages\ListAssetCategories;
use App\Models\AssetCategory;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeAssetCategoriesAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

it('redirects guests away from asset categories list', function (): void {
    get('/admin/asset-categories')->assertRedirect('/admin/login');
});

it('redirects guests away from create asset category page', function (): void {
    get('/admin/asset-categories/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating an asset category', function (): void {
    actingAs(makeAssetCategoriesAdminUser());

    Livewire::test(CreateAssetCategory::class)
        ->fillForm(['name' => '', 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required', 'status' => 'required']);
});

it('performs asset category crud operations', function (): void {
    actingAs(makeAssetCategoriesAdminUser());

    Livewire::test(CreateAssetCategory::class)
        ->fillForm(['name' => 'Electronics', 'status' => true])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = AssetCategory::query()->firstOrFail();
    Livewire::test(ListAssetCategories::class)->assertSuccessful()->assertSee('Electronics');

    Livewire::test(EditAssetCategory::class, ['record' => $record->getKey()])
        ->fillForm(['name' => 'Furniture', 'status' => false])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect((bool) $record->status)->toBeFalse();

    Livewire::test(EditAssetCategory::class, ['record' => $record->getKey()])->callAction('delete');
    expect(AssetCategory::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
