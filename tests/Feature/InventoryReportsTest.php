<?php

use App\Filament\Resources\InventoryReports\Pages\CreateInventoryReport;
use App\Filament\Resources\InventoryReports\Pages\EditInventoryReport;
use App\Filament\Resources\InventoryReports\Pages\ListInventoryReports;
use App\Models\Branch;
use App\Models\Company;
use App\Models\InventoryReport;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeInventoryReportsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createInventoryReportBranch(): Branch
{
    $company = Company::query()->create(['name' => 'Inventory Report Co', 'code' => 'IRCO1', 'status' => true]);
    return Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'Inventory Report Branch', 'code' => 'IRBR1', 'status' => true]);
}

it('redirects guests away from inventory reports list', function (): void { get('/admin/inventory-reports')->assertRedirect('/admin/login'); });
it('redirects guests away from create inventory report page', function (): void { get('/admin/inventory-reports/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating an inventory report', function (): void {
    actingAs(makeInventoryReportsAdminUser());

    Livewire::test(CreateInventoryReport::class)
        ->fillForm(['branch_id' => null, 'report_date' => null, 'total_inventory_value' => null])
        ->call('create')
        ->assertHasFormErrors(['branch_id' => 'required', 'report_date' => 'required', 'total_inventory_value' => 'required']);
});

it('performs inventory report crud operations', function (): void {
    actingAs(makeInventoryReportsAdminUser());
    $branch = createInventoryReportBranch();

    Livewire::test(CreateInventoryReport::class)
        ->fillForm(['branch_id' => $branch->getKey(), 'report_date' => '2026-07-21', 'total_inventory_value' => 12000])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = InventoryReport::query()->firstOrFail();
    Livewire::test(ListInventoryReports::class)->assertSuccessful()->assertSee('Inventory Report Branch');

    Livewire::test(EditInventoryReport::class, ['record' => $record->getKey()])
        ->fillForm(['branch_id' => $branch->getKey(), 'report_date' => '2026-07-22', 'total_inventory_value' => 15000])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect((float) $record->total_inventory_value)->toBe(15000.0);

    Livewire::test(EditInventoryReport::class, ['record' => $record->getKey()])->callAction('delete');
    expect(InventoryReport::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
