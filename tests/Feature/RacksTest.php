<?php

use App\Filament\Resources\Racks\Pages\CreateRack;
use App\Filament\Resources\Racks\Pages\EditRack;
use App\Filament\Resources\Racks\Pages\ListRacks;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Rack;
use App\Models\Role;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\WarehouseZone;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeRacksAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

function createRackZone(): WarehouseZone
{
    $company = Company::query()->create(['name' => 'Rack Company', 'code' => 'RKC01', 'status' => true]);
    $branch = Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'Rack Branch', 'code' => 'RKB01', 'status' => true]);
    $warehouse = Warehouse::query()->create(['branch_id' => $branch->getKey(), 'name' => 'Rack Warehouse', 'code' => 'RKW01', 'status' => true]);

    return WarehouseZone::query()->create(['warehouse_id' => $warehouse->getKey(), 'name' => 'Storage Zone', 'code' => 'RKZ01']);
}

it('redirects guests away from racks list', function (): void {
    get('/admin/racks')->assertRedirect('/admin/login');
});

it('redirects guests away from create rack page', function (): void {
    get('/admin/racks/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a rack', function (): void {
    actingAs(makeRacksAdminUser());

    Livewire::test(CreateRack::class)
        ->fillForm(['warehouse_zone_id' => null, 'name' => '', 'code' => ''])
        ->call('create')
        ->assertHasFormErrors(['warehouse_zone_id' => 'required', 'name' => 'required', 'code' => 'required']);
});

it('performs rack crud operations', function (): void {
    actingAs(makeRacksAdminUser());
    $zone = createRackZone();

    Livewire::test(CreateRack::class)
        ->fillForm(['warehouse_zone_id' => $zone->getKey(), 'name' => 'Rack A', 'code' => 'RACK-A'])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = Rack::query()->firstOrFail();

    Livewire::test(ListRacks::class)
        ->assertSuccessful()
        ->assertSee('Rack A');

    Livewire::test(EditRack::class, ['record' => $record->getKey()])
        ->fillForm(['warehouse_zone_id' => $zone->getKey(), 'name' => 'Rack B', 'code' => 'RACK-B'])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->name)->toBe('Rack B');

    Livewire::test(EditRack::class, ['record' => $record->getKey()])->callAction('delete');
    expect(Rack::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
