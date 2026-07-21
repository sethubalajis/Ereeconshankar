<?php

use App\Filament\Resources\AssetAllocations\Pages\CreateAssetAllocation;
use App\Filament\Resources\AssetAllocations\Pages\EditAssetAllocation;
use App\Filament\Resources\AssetAllocations\Pages\ListAssetAllocations;
use App\Models\Asset;
use App\Models\AssetAllocation;
use App\Models\AssetCategory;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeAssetAllocationsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createAssetAllocationSetup(): array
{
    $category = AssetCategory::query()->create(['name' => 'AA Cat', 'status' => true]);
    $company = Company::query()->create(['name' => 'AA Co', 'code' => 'AACO1', 'status' => true]);
    $branch = Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'AA Branch', 'code' => 'AAB01', 'status' => true]);
    $asset = Asset::query()->create([
        'asset_category_id' => $category->getKey(),
        'branch_id' => $branch->getKey(),
        'name' => 'AA Asset',
        'asset_code' => 'AA-AST-1',
        'purchase_cost' => 12000,
        'status' => 'active',
    ]);
    $employee = Employee::query()->create([
        'first_name' => 'Alloc Emp',
        'status' => 'active',
    ]);
    return compact('asset', 'employee');
}

it('redirects guests away from asset allocations list', function (): void {
    get('/admin/asset-allocations')->assertRedirect('/admin/login');
});

it('redirects guests away from create asset allocation page', function (): void {
    get('/admin/asset-allocations/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating an asset allocation', function (): void {
    actingAs(makeAssetAllocationsAdminUser());

    Livewire::test(CreateAssetAllocation::class)
        ->fillForm(['asset_id' => null, 'employee_id' => null, 'allocation_date' => null, 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['asset_id' => 'required', 'employee_id' => 'required', 'allocation_date' => 'required', 'status' => 'required']);
});

it('performs asset allocation crud operations', function (): void {
    actingAs(makeAssetAllocationsAdminUser());
    ['asset' => $asset, 'employee' => $employee] = createAssetAllocationSetup();

    Livewire::test(CreateAssetAllocation::class)
        ->fillForm([
            'asset_id' => $asset->getKey(),
            'employee_id' => $employee->getKey(),
            'allocation_date' => '2026-07-21',
            'return_date' => null,
            'status' => 'allocated',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = AssetAllocation::query()->firstOrFail();
    Livewire::test(ListAssetAllocations::class)->assertSuccessful()->assertSee('AA Asset');

    Livewire::test(EditAssetAllocation::class, ['record' => $record->getKey()])
        ->fillForm([
            'asset_id' => $asset->getKey(),
            'employee_id' => $employee->getKey(),
            'allocation_date' => '2026-07-21',
            'return_date' => '2026-07-30',
            'status' => 'returned',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->status)->toBe('returned');

    Livewire::test(EditAssetAllocation::class, ['record' => $record->getKey()])->callAction('delete');
    expect(AssetAllocation::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
