<?php

use App\Filament\Resources\ServiceRequests\Pages\CreateServiceRequest;
use App\Filament\Resources\ServiceRequests\Pages\EditServiceRequest;
use App\Filament\Resources\ServiceRequests\Pages\ListServiceRequests;
use App\Models\Customer;
use App\Models\Role;
use App\Models\ServiceRequest;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeServiceRequestsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

function createServiceRequestCustomer(): Customer
{
    return Customer::query()->create([
        'name' => 'Service Customer',
        'phone' => '9000001001',
        'status' => true,
    ]);
}

it('redirects guests away from service requests list', function (): void {
    get('/admin/service-requests')->assertRedirect('/admin/login');
});

it('redirects guests away from create service request page', function (): void {
    get('/admin/service-requests/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a service request', function (): void {
    actingAs(makeServiceRequestsAdminUser());

    Livewire::test(CreateServiceRequest::class)
        ->fillForm([
            'customer_id' => null,
            'request_date' => null,
            'status' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'customer_id' => 'required',
            'request_date' => 'required',
            'status' => 'required',
        ]);
});

it('performs service request crud operations', function (): void {
    actingAs(makeServiceRequestsAdminUser());
    $customer = createServiceRequestCustomer();

    Livewire::test(CreateServiceRequest::class)
        ->fillForm([
            'customer_id' => $customer->getKey(),
            'request_date' => '2026-07-21',
            'issue_description' => 'Power issue in device',
            'status' => 'open',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = ServiceRequest::query()->firstOrFail();

    Livewire::test(ListServiceRequests::class)
        ->assertSuccessful()
        ->assertSee($record->request_number);

    Livewire::test(EditServiceRequest::class, ['record' => $record->getKey()])
        ->fillForm([
            'customer_id' => $customer->getKey(),
            'request_number' => 'SR-0002',
            'request_date' => '2026-07-22',
            'issue_description' => 'Updated issue details',
            'status' => 'in_progress',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->request_number)->toBe('SR-0002');

    Livewire::test(EditServiceRequest::class, ['record' => $record->getKey()])->callAction('delete');
    expect(ServiceRequest::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
