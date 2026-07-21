<?php

use App\Filament\Resources\Warehouses\Pages\CreateWarehouse;
use App\Filament\Resources\Warehouses\Pages\EditWarehouse;
use App\Filament\Resources\Warehouses\Pages\ListWarehouses;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use App\Models\Warehouse;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeWarehousesAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createWarehouseBranch(): Branch
{
    $company = Company::query()->create(['name' => 'WH Co', 'code' => 'WHCO1', 'status' => true]);
    return Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'WH Branch', 'code' => 'WHB01', 'status' => true]);
}

it('redirects guests away from warehouses list', function (): void {
    get('/admin/warehouses')->assertRedirect('/admin/login');
});

it('redirects guests away from create warehouse page', function (): void {
    get('/admin/warehouses/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a warehouse', function (): void {
    actingAs(makeWarehousesAdminUser());

    Livewire::test(CreateWarehouse::class)
        ->fillForm(['branch_id' => null, 'name' => '', 'code' => '', 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['branch_id' => 'required', 'name' => 'required', 'code' => 'required', 'status' => 'required']);
});

it('performs warehouse crud operations', function (): void {
    actingAs(makeWarehousesAdminUser());
    $branch = createWarehouseBranch();

    Livewire::test(CreateWarehouse::class)
        ->fillForm([
            'branch_id' => $branch->getKey(),
            'name' => 'Main Warehouse',
            'code' => 'MWH1',
            'address' => 'Central Road',
            'capacity' => 1000,
            'status' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = Warehouse::query()->firstOrFail();
    Livewire::test(ListWarehouses::class)->assertSuccessful()->assertSee('Main Warehouse');

    Livewire::test(EditWarehouse::class, ['record' => $record->getKey()])
        ->fillForm([
            'branch_id' => $branch->getKey(),
            'name' => 'Secondary Warehouse',
            'code' => 'SWH1',
            'address' => 'North Road',
            'capacity' => 1200,
            'status' => false,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->code)->toBe('SWH1');

    Livewire::test(EditWarehouse::class, ['record' => $record->getKey()])->callAction('delete');
    expect(Warehouse::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
