<?php

use App\Filament\Resources\Assets\Pages\CreateAsset;
use App\Filament\Resources\Assets\Pages\EditAsset;
use App\Filament\Resources\Assets\Pages\ListAssets;
use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeAssetsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

function createAssetTestBranchAndCategory(): array
{
    $company = Company::query()->create(['name' => 'Asset Co', 'code' => 'ASTCO1', 'status' => true]);
    $branch = Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'Asset Branch', 'code' => 'ASTBR1', 'status' => true]);
    $category = AssetCategory::query()->create(['name' => 'Computers', 'status' => true]);

    return compact('branch', 'category');
}

it('redirects guests away from assets list', function (): void {
    get('/admin/assets')->assertRedirect('/admin/login');
});

it('redirects guests away from create asset page', function (): void {
    get('/admin/assets/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating an asset', function (): void {
    actingAs(makeAssetsAdminUser());

    Livewire::test(CreateAsset::class)
        ->fillForm(['asset_category_id' => null, 'branch_id' => null, 'name' => '', 'asset_code' => '', 'purchase_date' => null, 'purchase_cost' => null, 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['asset_category_id' => 'required', 'branch_id' => 'required', 'name' => 'required', 'asset_code' => 'required', 'purchase_cost' => 'required', 'status' => 'required']);
});

it('performs asset crud operations', function (): void {
    actingAs(makeAssetsAdminUser());

    ['branch' => $branch, 'category' => $category] = createAssetTestBranchAndCategory();

    Livewire::test(CreateAsset::class)
        ->fillForm(['asset_category_id' => $category->getKey(), 'branch_id' => $branch->getKey(), 'name' => 'Desktop', 'asset_code' => 'AST001', 'purchase_date' => '2026-07-21', 'purchase_cost' => 1000, 'status' => 'active'])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = Asset::query()->where('asset_code', 'AST001')->firstOrFail();

    Livewire::test(ListAssets::class)->assertSuccessful()->assertSee('Desktop');

    Livewire::test(EditAsset::class, ['record' => $record->getKey()])
        ->fillForm(['asset_category_id' => $category->getKey(), 'branch_id' => $branch->getKey(), 'name' => 'Laptop', 'asset_code' => 'AST002', 'purchase_date' => '2026-07-22', 'purchase_cost' => 1200, 'status' => 'maintenance'])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->name)->toBe('Laptop');
    expect($record->asset_code)->toBe('AST002');

    Livewire::test(EditAsset::class, ['record' => $record->getKey()])->callAction('delete');
    expect(Asset::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
