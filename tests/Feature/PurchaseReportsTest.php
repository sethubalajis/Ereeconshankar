<?php

use App\Filament\Resources\PurchaseReports\Pages\CreatePurchaseReport;
use App\Filament\Resources\PurchaseReports\Pages\EditPurchaseReport;
use App\Filament\Resources\PurchaseReports\Pages\ListPurchaseReports;
use App\Models\Branch;
use App\Models\Company;
use App\Models\PurchaseReport;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makePurchaseReportsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

function createPurchaseReportBranch(): Branch
{
    $company = Company::query()->create(['name' => 'PR Company', 'code' => 'PRC01', 'status' => true]);

    return Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'PR Branch', 'code' => 'PRB01', 'status' => true]);
}

it('redirects guests away from purchase reports list', function (): void {
    get('/admin/purchase-reports')->assertRedirect('/admin/login');
});

it('redirects guests away from create purchase report page', function (): void {
    get('/admin/purchase-reports/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a purchase report', function (): void {
    actingAs(makePurchaseReportsAdminUser());

    Livewire::test(CreatePurchaseReport::class)
        ->fillForm(['branch_id' => null, 'from_date' => null, 'to_date' => null, 'total_purchases' => null])
        ->call('create')
        ->assertHasFormErrors(['branch_id' => 'required', 'from_date' => 'required', 'to_date' => 'required', 'total_purchases' => 'required']);
});

it('performs purchase report crud operations', function (): void {
    actingAs(makePurchaseReportsAdminUser());
    $branch = createPurchaseReportBranch();

    Livewire::test(CreatePurchaseReport::class)
        ->fillForm([
            'branch_id' => $branch->getKey(),
            'from_date' => '2026-07-01',
            'to_date' => '2026-07-21',
            'report_date' => '2026-07-21',
            'total_purchases' => 5000,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = PurchaseReport::query()->firstOrFail();
    expect($record->from_date->format('Y-m-d'))->toBe('2026-07-01');
    expect($record->to_date->format('Y-m-d'))->toBe('2026-07-21');

    Livewire::test(ListPurchaseReports::class)
        ->assertSuccessful()
        ->assertSee('PR Branch');

    Livewire::test(EditPurchaseReport::class, ['record' => $record->getKey()])
        ->fillForm([
            'branch_id' => $branch->getKey(),
            'from_date' => '2026-07-01',
            'to_date' => '2026-07-22',
            'report_date' => '2026-07-22',
            'total_purchases' => 7000,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect((float) $record->total_purchases)->toBe(7000.0);
    expect($record->to_date->format('Y-m-d'))->toBe('2026-07-22');

    Livewire::test(EditPurchaseReport::class, ['record' => $record->getKey()])->callAction('delete');
    expect(PurchaseReport::query()->whereKey($record->getKey())->exists())->toBeFalse();
});

it('automatically updates total purchases when branch and date are selected', function (): void {
    actingAs(makePurchaseReportsAdminUser());
    $branch = createPurchaseReportBranch();
    $vendor = \App\Models\Vendor::query()->create(['name' => 'Test Vendor', 'code' => 'VEND01', 'status' => true]);

    \App\Models\PurchaseOrder::query()->create([
        'vendor_id' => $vendor->id,
        'branch_id' => $branch->id,
        'po_date' => '2026-07-25',
        'total_amount' => 1500.50,
        'status' => 'approved',
    ]);

    \App\Models\PurchaseOrder::query()->create([
        'vendor_id' => $vendor->id,
        'branch_id' => $branch->id,
        'po_date' => '2026-07-25',
        'total_amount' => 2500.00,
        'status' => 'completed',
    ]);

    Livewire::test(CreatePurchaseReport::class)
        ->fillForm([
            'branch_id' => $branch->id,
            'from_date' => '2026-07-20',
            'to_date' => '2026-07-25',
        ])
        ->assertFormSet(['total_purchases' => 4000.50]);
});

it('downloads detailed purchase report pdf', function (): void {
    actingAs(makePurchaseReportsAdminUser());
    $branch = createPurchaseReportBranch();
    $vendor = \App\Models\Vendor::query()->create(['name' => 'Test Vendor', 'code' => 'VEND02', 'status' => true]);

    $po = \App\Models\PurchaseOrder::query()->create([
        'vendor_id' => $vendor->id,
        'branch_id' => $branch->id,
        'po_date' => '2026-07-25',
        'total_amount' => 3000.00,
        'status' => 'approved',
    ]);

    $report = PurchaseReport::query()->create([
        'branch_id' => $branch->id,
        'from_date' => '2026-07-20',
        'to_date' => '2026-07-25',
        'report_date' => '2026-07-25',
        'total_purchases' => 3000.00,
    ]);

    $response = \App\Helpers\ReportPdfHelper::downloadPurchaseReportPdf($report);
    expect($response)->toBeInstanceOf(\Symfony\Component\HttpFoundation\StreamedResponse::class);
});
