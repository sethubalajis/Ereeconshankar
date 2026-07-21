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

function createAssetsSetup(): array
{
    $category = AssetCategory::query()->create(['name' => 'Assets Category', 'status' => true]);
    $company = Company::query()->create(['name' => 'Asset Co', 'code' => 'ASCO1', 'status' => true]);
    $branch = Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'Asset Branch', 'code' => 'ASBR1', 'status' => true]);
    return compact('category', 'branch');
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
        ->fillForm(['asset_category_id' => null, 'branch_id' => null, 'name' => '', 'asset_code' => '', 'purchase_cost' => null, 'status' => null])
        ->call('create')
        ->assertHasFormErrors([
            'asset_category_id' => 'required',
            'branch_id' => 'required',
            'name' => 'required',
            'asset_code' => 'required',
            'purchase_cost' => 'required',
            'status' => 'required',
        ]);
});

it('performs asset crud operations', function (): void {
    actingAs(makeAssetsAdminUser());
    ['category' => $category, 'branch' => $branch] = createAssetsSetup();

    Livewire::test(CreateAsset::class)
        ->fillForm([
            'asset_category_id' => $category->getKey(),
            'branch_id' => $branch->getKey(),
            'name' => 'Laptop A',
            'asset_code' => 'AST-001',
            'purchase_date' => '2026-07-21',
            'purchase_cost' => 50000,
            'status' => 'active',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = Asset::query()->firstOrFail();
    Livewire::test(ListAssets::class)->assertSuccessful()->assertSee('Laptop A');

    Livewire::test(EditAsset::class, ['record' => $record->getKey()])
        ->fillForm([
            'asset_category_id' => $category->getKey(),
            'branch_id' => $branch->getKey(),
            'name' => 'Laptop B',
            'asset_code' => 'AST-002',
            'purchase_date' => '2026-07-22',
            'purchase_cost' => 52000,
            'status' => 'maintenance',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->asset_code)->toBe('AST-002');

    Livewire::test(EditAsset::class, ['record' => $record->getKey()])->callAction('delete');
    expect(Asset::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
