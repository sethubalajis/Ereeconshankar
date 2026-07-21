<?php

use App\Filament\Resources\Bins\Pages\CreateBin;
use App\Filament\Resources\Bins\Pages\EditBin;
use App\Filament\Resources\Bins\Pages\ListBins;
use App\Models\Bin;
use App\Models\Rack;
use App\Models\Role;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\WarehouseZone;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeBinsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createBinRack(): Rack
{
    $company = App\Models\Company::query()->create(['name' => 'Bin Co', 'code' => 'BINCO1', 'status' => true]);
    $branch = App\Models\Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'Bin Branch', 'code' => 'BINBR1', 'status' => true]);
    $warehouse = Warehouse::query()->create(['branch_id' => $branch->getKey(), 'name' => 'Main Warehouse', 'code' => 'WH001', 'status' => true]);
    $zone = WarehouseZone::query()->create(['warehouse_id' => $warehouse->getKey(), 'name' => 'Storage Zone', 'code' => 'WZ001', 'type' => 'Storage']);
    return Rack::query()->create(['warehouse_zone_id' => $zone->getKey(), 'name' => 'Rack A', 'code' => 'RCK001']);
}

it('redirects guests away from bins list', function (): void { get('/admin/bins')->assertRedirect('/admin/login'); });
it('redirects guests away from create bin page', function (): void { get('/admin/bins/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a bin', function (): void {
    actingAs(makeBinsAdminUser());
    Livewire::test(CreateBin::class)
        ->fillForm(['rack_id' => null, 'name' => '', 'code' => '', 'max_weight' => null])
        ->call('create')
        ->assertHasFormErrors(['rack_id' => 'required', 'name' => 'required', 'code' => 'required']);
});

it('performs bin crud operations', function (): void {
    actingAs(makeBinsAdminUser());
    $rack = createBinRack();

    Livewire::test(CreateBin::class)
        ->fillForm(['rack_id' => $rack->getKey(), 'name' => 'Bin 1', 'code' => 'BIN001', 'max_weight' => 50])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = Bin::query()->where('code', 'BIN001')->firstOrFail();
    Livewire::test(ListBins::class)->assertSuccessful()->assertSee('Bin 1');

    Livewire::test(EditBin::class, ['record' => $record->getKey()])
        ->fillForm(['rack_id' => $rack->getKey(), 'name' => 'Bin 2', 'code' => 'BIN002', 'max_weight' => 60])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->name)->toBe('Bin 2');

    Livewire::test(EditBin::class, ['record' => $record->getKey()])->callAction('delete');
    expect(Bin::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
