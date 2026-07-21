<?php

use App\Filament\Resources\SalesReports\Pages\CreateSalesReport;
use App\Filament\Resources\SalesReports\Pages\EditSalesReport;
use App\Filament\Resources\SalesReports\Pages\ListSalesReports;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Role;
use App\Models\SalesReport;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeSalesReportsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

function createSalesReportsBranch(): Branch
{
    $company = Company::query()->create([
        'name' => 'Sales Co',
        'code' => 'SALC1',
        'status' => true,
    ]);

    return Branch::query()->create([
        'company_id' => $company->getKey(),
        'name' => 'Sales Branch',
        'code' => 'SALB1',
        'status' => true,
    ]);
}

it('redirects guests away from sales reports list', function (): void {
    get('/admin/sales-reports')->assertRedirect('/admin/login');
});

it('redirects guests away from create sales report page', function (): void {
    get('/admin/sales-reports/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a sales report', function (): void {
    actingAs(makeSalesReportsAdminUser());

    Livewire::test(CreateSalesReport::class)
        ->fillForm([
            'branch_id' => null,
            'report_date' => null,
            'total_sales' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'branch_id' => 'required',
            'report_date' => 'required',
            'total_sales' => 'required',
        ]);
});

it('performs sales report crud operations', function (): void {
    actingAs(makeSalesReportsAdminUser());
    $branch = createSalesReportsBranch();

    Livewire::test(CreateSalesReport::class)
        ->fillForm([
            'branch_id' => $branch->getKey(),
            'report_date' => '2026-07-21',
            'total_sales' => 10000,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = SalesReport::query()->firstOrFail();

    Livewire::test(ListSalesReports::class)
        ->assertSuccessful()
        ->assertSee('Sales Branch');

    Livewire::test(EditSalesReport::class, ['record' => $record->getKey()])
        ->fillForm([
            'branch_id' => $branch->getKey(),
            'report_date' => '2026-07-22',
            'total_sales' => 12000,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect((float) $record->total_sales)->toBe(12000.0);

    Livewire::test(EditSalesReport::class, ['record' => $record->getKey()])->callAction('delete');
    expect(SalesReport::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
