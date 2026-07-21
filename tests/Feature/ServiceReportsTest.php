<?php

use App\Filament\Resources\ServiceReports\Pages\CreateServiceReport;
use App\Filament\Resources\ServiceReports\Pages\EditServiceReport;
use App\Filament\Resources\ServiceReports\Pages\ListServiceReports;
use App\Models\Customer;
use App\Models\Role;
use App\Models\ServiceReport;
use App\Models\ServiceRequest;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeServiceReportsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

function createServiceReportRequest(): ServiceRequest
{
    $customer = Customer::query()->create([
        'name' => 'Report Customer',
        'phone' => '9000001003',
        'status' => true,
    ]);

    return ServiceRequest::query()->create([
        'customer_id' => $customer->getKey(),
        'request_number' => 'SR-REP-1',
        'request_date' => '2026-07-21',
        'status' => 'open',
    ]);
}

it('redirects guests away from service reports list', function (): void {
    get('/admin/service-reports')->assertRedirect('/admin/login');
});

it('redirects guests away from create service report page', function (): void {
    get('/admin/service-reports/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a service report', function (): void {
    actingAs(makeServiceReportsAdminUser());

    Livewire::test(CreateServiceReport::class)
        ->fillForm([
            'service_request_id' => null,
            'report_date' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'service_request_id' => 'required',
            'report_date' => 'required',
        ]);
});

it('performs service report crud operations', function (): void {
    actingAs(makeServiceReportsAdminUser());
    $request = createServiceReportRequest();

    Livewire::test(CreateServiceReport::class)
        ->fillForm([
            'service_request_id' => $request->getKey(),
            'resolution_details' => 'Initial diagnosis done',
            'report_date' => '2026-07-21',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = ServiceReport::query()->firstOrFail();

    Livewire::test(ListServiceReports::class)
        ->assertSuccessful()
        ->assertSee('SR-REP-1');

    Livewire::test(EditServiceReport::class, ['record' => $record->getKey()])
        ->fillForm([
            'service_request_id' => $request->getKey(),
            'resolution_details' => 'Issue resolved fully',
            'report_date' => '2026-07-22',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect((string) $record->report_date)->toBe('2026-07-22');

    Livewire::test(EditServiceReport::class, ['record' => $record->getKey()])->callAction('delete');
    expect(ServiceReport::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
