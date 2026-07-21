<?php

use App\Filament\Resources\AssetTransfers\Pages\CreateAssetTransfer;
use App\Filament\Resources\AssetTransfers\Pages\EditAssetTransfer;
use App\Filament\Resources\AssetTransfers\Pages\ListAssetTransfers;
use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\AssetTransfer;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeAssetTransfersAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createAssetTransferSetup(): array
{
    $category = AssetCategory::query()->create(['name' => 'AT Cat', 'status' => true]);
    $company = Company::query()->create(['name' => 'AT Co', 'code' => 'ATCO1', 'status' => true]);
    $fromBranch = Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'AT From', 'code' => 'ATF01', 'status' => true]);
    $toBranch = Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'AT To', 'code' => 'ATT01', 'status' => true]);
    $asset = Asset::query()->create([
        'asset_category_id' => $category->getKey(),
        'branch_id' => $fromBranch->getKey(),
        'name' => 'AT Asset',
        'asset_code' => 'AT-AST-1',
        'purchase_cost' => 14000,
        'status' => 'active',
    ]);
    return compact('asset', 'fromBranch', 'toBranch');
}

it('redirects guests away from asset transfers list', function (): void {
    get('/admin/asset-transfers')->assertRedirect('/admin/login');
});

it('redirects guests away from create asset transfer page', function (): void {
    get('/admin/asset-transfers/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating an asset transfer', function (): void {
    actingAs(makeAssetTransfersAdminUser());

    Livewire::test(CreateAssetTransfer::class)
        ->fillForm(['asset_id' => null, 'from_branch_id' => null, 'to_branch_id' => null, 'transfer_date' => null, 'status' => null])
        ->call('create')
        ->assertHasFormErrors([
            'asset_id' => 'required',
            'from_branch_id' => 'required',
            'to_branch_id' => 'required',
            'transfer_date' => 'required',
            'status' => 'required',
        ]);
});

it('performs asset transfer crud operations', function (): void {
    actingAs(makeAssetTransfersAdminUser());
    ['asset' => $asset, 'fromBranch' => $fromBranch, 'toBranch' => $toBranch] = createAssetTransferSetup();

    Livewire::test(CreateAssetTransfer::class)
        ->fillForm([
            'asset_id' => $asset->getKey(),
            'from_branch_id' => $fromBranch->getKey(),
            'to_branch_id' => $toBranch->getKey(),
            'transfer_date' => '2026-07-21',
            'status' => 'pending',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = AssetTransfer::query()->firstOrFail();
    Livewire::test(ListAssetTransfers::class)->assertSuccessful()->assertSee('AT Asset');

    Livewire::test(EditAssetTransfer::class, ['record' => $record->getKey()])
        ->fillForm([
            'asset_id' => $asset->getKey(),
            'from_branch_id' => $fromBranch->getKey(),
            'to_branch_id' => $toBranch->getKey(),
            'transfer_date' => '2026-07-22',
            'status' => 'completed',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->status)->toBe('completed');

    Livewire::test(EditAssetTransfer::class, ['record' => $record->getKey()])->callAction('delete');
    expect(AssetTransfer::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
