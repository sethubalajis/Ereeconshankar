<?php

use App\Filament\Resources\AssetDisposals\Pages\CreateAssetDisposal;
use App\Filament\Resources\AssetDisposals\Pages\EditAssetDisposal;
use App\Filament\Resources\AssetDisposals\Pages\ListAssetDisposals;
use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\AssetDisposal;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeAssetDisposalsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createAssetForDisposal(): Asset
{
    $category = AssetCategory::query()->create(['name' => 'ADS Cat', 'status' => true]);
    $company = Company::query()->create(['name' => 'ADS Co', 'code' => 'ADSCO', 'status' => true]);
    $branch = Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'ADS Branch', 'code' => 'ADSB1', 'status' => true]);
    return Asset::query()->create([
        'asset_category_id' => $category->getKey(),
        'branch_id' => $branch->getKey(),
        'name' => 'ADS Asset',
        'asset_code' => 'ADS-AST-1',
        'purchase_cost' => 6000,
        'status' => 'active',
    ]);
}

it('redirects guests away from asset disposals list', function (): void {
    get('/admin/asset-disposals')->assertRedirect('/admin/login');
});

it('redirects guests away from create asset disposal page', function (): void {
    get('/admin/asset-disposals/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating an asset disposal', function (): void {
    actingAs(makeAssetDisposalsAdminUser());

    Livewire::test(CreateAssetDisposal::class)
        ->fillForm(['asset_id' => null, 'disposal_date' => null, 'sale_value' => null])
        ->call('create')
        ->assertHasFormErrors(['asset_id' => 'required', 'disposal_date' => 'required', 'sale_value' => 'required']);
});

it('performs asset disposal crud operations', function (): void {
    actingAs(makeAssetDisposalsAdminUser());
    $asset = createAssetForDisposal();

    Livewire::test(CreateAssetDisposal::class)
        ->fillForm([
            'asset_id' => $asset->getKey(),
            'disposal_date' => '2026-07-21',
            'sale_value' => 3500,
            'reason' => 'Damaged beyond repair',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = AssetDisposal::query()->firstOrFail();
    Livewire::test(ListAssetDisposals::class)->assertSuccessful()->assertSee('ADS Asset');

    Livewire::test(EditAssetDisposal::class, ['record' => $record->getKey()])
        ->fillForm([
            'asset_id' => $asset->getKey(),
            'disposal_date' => '2026-07-23',
            'sale_value' => 3200,
            'reason' => 'Obsolete',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect((float) $record->sale_value)->toBe(3200.0);

    Livewire::test(EditAssetDisposal::class, ['record' => $record->getKey()])->callAction('delete');
    expect(AssetDisposal::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
