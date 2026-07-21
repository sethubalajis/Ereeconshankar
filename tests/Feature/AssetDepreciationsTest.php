<?php

use App\Filament\Resources\AssetDepreciations\Pages\CreateAssetDepreciation;
use App\Filament\Resources\AssetDepreciations\Pages\EditAssetDepreciation;
use App\Filament\Resources\AssetDepreciations\Pages\ListAssetDepreciations;
use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\AssetDepreciation;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeAssetDepreciationsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createAssetForDepreciation(): Asset
{
    $category = AssetCategory::query()->create(['name' => 'AD Cat', 'status' => true]);
    $company = Company::query()->create(['name' => 'AD Co', 'code' => 'ADCO1', 'status' => true]);
    $branch = Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'AD Branch', 'code' => 'ADB01', 'status' => true]);
    return Asset::query()->create([
        'asset_category_id' => $category->getKey(),
        'branch_id' => $branch->getKey(),
        'name' => 'AD Asset',
        'asset_code' => 'AD-AST-1',
        'purchase_cost' => 9000,
        'status' => 'active',
    ]);
}

it('redirects guests away from asset depreciations list', function (): void {
    get('/admin/asset-depreciations')->assertRedirect('/admin/login');
});

it('redirects guests away from create asset depreciation page', function (): void {
    get('/admin/asset-depreciations/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating an asset depreciation', function (): void {
    actingAs(makeAssetDepreciationsAdminUser());

    Livewire::test(CreateAssetDepreciation::class)
        ->fillForm(['asset_id' => null, 'depreciation_date' => null, 'amount' => null, 'current_value' => null])
        ->call('create')
        ->assertHasFormErrors(['asset_id' => 'required', 'depreciation_date' => 'required', 'amount' => 'required', 'current_value' => 'required']);
});

it('performs asset depreciation crud operations', function (): void {
    actingAs(makeAssetDepreciationsAdminUser());
    $asset = createAssetForDepreciation();

    Livewire::test(CreateAssetDepreciation::class)
        ->fillForm([
            'asset_id' => $asset->getKey(),
            'depreciation_date' => '2026-07-21',
            'amount' => 500,
            'current_value' => 8500,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = AssetDepreciation::query()->firstOrFail();
    Livewire::test(ListAssetDepreciations::class)->assertSuccessful()->assertSee('AD Asset');

    Livewire::test(EditAssetDepreciation::class, ['record' => $record->getKey()])
        ->fillForm([
            'asset_id' => $asset->getKey(),
            'depreciation_date' => '2026-07-22',
            'amount' => 700,
            'current_value' => 7800,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect((float) $record->amount)->toBe(700.0);

    Livewire::test(EditAssetDepreciation::class, ['record' => $record->getKey()])->callAction('delete');
    expect(AssetDepreciation::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
