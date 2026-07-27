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
        ->fillForm(['branch_id' => null, 'from_date' => null, 'to_date' => null, 'total_inventory_value' => null])
        ->call('create')
        ->assertHasFormErrors(['branch_id' => 'required', 'from_date' => 'required', 'to_date' => 'required', 'total_inventory_value' => 'required']);
});

it('performs inventory report crud operations', function (): void {
    actingAs(makeInventoryReportsAdminUser());
    $branch = createInventoryReportBranch();

    Livewire::test(CreateInventoryReport::class)
        ->fillForm([
            'branch_id' => $branch->getKey(),
            'from_date' => '2026-07-01',
            'to_date' => '2026-07-21',
            'report_date' => '2026-07-21',
            'total_inventory_value' => 12000,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = InventoryReport::query()->firstOrFail();
    expect($record->from_date->format('Y-m-d'))->toBe('2026-07-01');
    expect($record->to_date->format('Y-m-d'))->toBe('2026-07-21');

    Livewire::test(ListInventoryReports::class)->assertSuccessful()->assertSee('Inventory Report Branch');

    Livewire::test(EditInventoryReport::class, ['record' => $record->getKey()])
        ->fillForm([
            'branch_id' => $branch->getKey(),
            'from_date' => '2026-07-01',
            'to_date' => '2026-07-22',
            'report_date' => '2026-07-22',
            'total_inventory_value' => 15000,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect((float) $record->total_inventory_value)->toBe(15000.0);
    expect($record->to_date->format('Y-m-d'))->toBe('2026-07-22');

    Livewire::test(EditInventoryReport::class, ['record' => $record->getKey()])->callAction('delete');
    expect(InventoryReport::query()->whereKey($record->getKey())->exists())->toBeFalse();
});

it('automatically updates total inventory value when branch and date are selected', function (): void {
    actingAs(makeInventoryReportsAdminUser());
    $branch = createInventoryReportBranch();

    $product = \App\Models\Product::query()->create(['name' => 'Test Product', 'code' => 'PROD01', 'is_active' => true]);
    $variant = \App\Models\ProductVariant::query()->create(['product_id' => $product->id, 'sku' => 'SKU01', 'cost_price' => 150.00, 'selling_price' => 200.00, 'is_active' => true]);

    \App\Models\BranchInventory::query()->create([
        'branch_id' => $branch->id,
        'product_id' => $product->id,
        'product_variant_id' => $variant->id,
        'quantity' => 20,
    ]);

    Livewire::test(CreateInventoryReport::class)
        ->fillForm([
            'branch_id' => $branch->id,
            'from_date' => '2026-07-20',
            'to_date' => '2026-07-25',
        ])
        ->assertFormSet(['total_inventory_value' => 3000.00]);
});

it('downloads detailed inventory report pdf', function (): void {
    actingAs(makeInventoryReportsAdminUser());
    $branch = createInventoryReportBranch();

    $report = InventoryReport::query()->create([
        'branch_id' => $branch->id,
        'from_date' => '2026-07-20',
        'to_date' => '2026-07-25',
        'report_date' => '2026-07-25',
        'total_inventory_value' => 5000.00,
    ]);

    $response = \App\Helpers\ReportPdfHelper::downloadInventoryReportPdf($report);
    expect($response)->toBeInstanceOf(\Symfony\Component\HttpFoundation\StreamedResponse::class);
});
