<?php

use App\Filament\Resources\AssetMaintenances\Pages\CreateAssetMaintenance;
use App\Filament\Resources\AssetMaintenances\Pages\EditAssetMaintenance;
use App\Filament\Resources\AssetMaintenances\Pages\ListAssetMaintenances;
use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\AssetMaintenance;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeAssetMaintenancesAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createAssetForMaintenance(): Asset
{
    $category = AssetCategory::query()->create(['name' => 'AM Cat', 'status' => true]);
    $company = Company::query()->create(['name' => 'AM Co', 'code' => 'AMCO1', 'status' => true]);
    $branch = Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'AM Branch', 'code' => 'AMB01', 'status' => true]);
    return Asset::query()->create([
        'asset_category_id' => $category->getKey(),
        'branch_id' => $branch->getKey(),
        'name' => 'AM Asset',
        'asset_code' => 'AM-AST-1',
        'purchase_cost' => 11000,
        'status' => 'active',
    ]);
}

it('redirects guests away from asset maintenances list', function (): void {
    get('/admin/asset-maintenances')->assertRedirect('/admin/login');
});

it('redirects guests away from create asset maintenance page', function (): void {
    get('/admin/asset-maintenances/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating an asset maintenance', function (): void {
    actingAs(makeAssetMaintenancesAdminUser());

    Livewire::test(CreateAssetMaintenance::class)
        ->fillForm(['asset_id' => null, 'maintenance_date' => null, 'cost' => null])
        ->call('create')
        ->assertHasFormErrors(['asset_id' => 'required', 'maintenance_date' => 'required', 'cost' => 'required']);
});

it('performs asset maintenance crud operations', function (): void {
    actingAs(makeAssetMaintenancesAdminUser());
    $asset = createAssetForMaintenance();

    Livewire::test(CreateAssetMaintenance::class)
        ->fillForm([
            'asset_id' => $asset->getKey(),
            'maintenance_date' => '2026-07-21',
            'cost' => 1500,
            'description' => 'Preventive maintenance',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = AssetMaintenance::query()->firstOrFail();
    Livewire::test(ListAssetMaintenances::class)->assertSuccessful()->assertSee('AM Asset');

    Livewire::test(EditAssetMaintenance::class, ['record' => $record->getKey()])
        ->fillForm([
            'asset_id' => $asset->getKey(),
            'maintenance_date' => '2026-07-22',
            'cost' => 1700,
            'description' => 'Corrective maintenance',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect((float) $record->cost)->toBe(1700.0);

    Livewire::test(EditAssetMaintenance::class, ['record' => $record->getKey()])->callAction('delete');
    expect(AssetMaintenance::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
