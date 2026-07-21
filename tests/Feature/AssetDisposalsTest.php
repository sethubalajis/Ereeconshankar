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

function createAssetDisposalAsset(): Asset
{
    $company = Company::query()->create(['name' => 'Disposal Co', 'code' => 'DISC01', 'status' => true]);
    $branch = Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'Disposal Branch', 'code' => 'DSBR1', 'status' => true]);
    $category = AssetCategory::query()->create(['name' => 'Disposal Assets', 'status' => true]);

    return Asset::query()->create([
        'asset_category_id' => $category->getKey(),
        'branch_id' => $branch->getKey(),
        'name' => 'Old Printer',
        'asset_code' => 'DSP001',
        'purchase_cost' => 800,
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
        ->fillForm(['asset_id' => null, 'disposal_date' => null, 'sale_value' => null, 'reason' => ''])
        ->call('create')
        ->assertHasFormErrors(['asset_id' => 'required', 'disposal_date' => 'required', 'sale_value' => 'required']);
});

it('performs asset disposal crud operations', function (): void {
    actingAs(makeAssetDisposalsAdminUser());

    $asset = createAssetDisposalAsset();

    Livewire::test(CreateAssetDisposal::class)
        ->fillForm(['asset_id' => $asset->getKey(), 'disposal_date' => '2026-07-21', 'sale_value' => 250, 'reason' => 'Scrapped'])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = AssetDisposal::query()->firstOrFail();

    Livewire::test(ListAssetDisposals::class)->assertSuccessful()->assertSee('250');

    Livewire::test(EditAssetDisposal::class, ['record' => $record->getKey()])
        ->fillForm(['asset_id' => $asset->getKey(), 'disposal_date' => '2026-07-22', 'sale_value' => 300, 'reason' => 'Sold'])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect((float) $record->sale_value)->toBe(300.0);

    Livewire::test(EditAssetDisposal::class, ['record' => $record->getKey()])->callAction('delete');
    expect(AssetDisposal::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
