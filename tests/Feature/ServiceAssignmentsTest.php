<?php

use App\Filament\Resources\ServiceAssignments\Pages\CreateServiceAssignment;
use App\Filament\Resources\ServiceAssignments\Pages\EditServiceAssignment;
use App\Filament\Resources\ServiceAssignments\Pages\ListServiceAssignments;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Role;
use App\Models\ServiceAssignment;
use App\Models\ServiceRequest;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeServiceAssignmentsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

function createServiceAssignmentSetup(): array
{
    $customer = Customer::query()->create([
        'name' => 'Assign Customer',
        'phone' => '9000001002',
        'status' => true,
    ]);
    $request = ServiceRequest::query()->create([
        'customer_id' => $customer->getKey(),
        'request_number' => 'SR-ASSIGN-1',
        'request_date' => '2026-07-21',
        'status' => 'open',
    ]);
    $employee = Employee::query()->create([
        'first_name' => 'Assign',
        'last_name' => 'Tech',
        'status' => 'active',
    ]);

    return compact('request', 'employee');
}

it('redirects guests away from service assignments list', function (): void {
    get('/admin/service-assignments')->assertRedirect('/admin/login');
});

it('redirects guests away from create service assignment page', function (): void {
    get('/admin/service-assignments/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a service assignment', function (): void {
    actingAs(makeServiceAssignmentsAdminUser());

    Livewire::test(CreateServiceAssignment::class)
        ->fillForm([
            'service_request_id' => null,
            'employee_id' => null,
            'assigned_date' => null,
            'status' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'service_request_id' => 'required',
            'employee_id' => 'required',
            'assigned_date' => 'required',
            'status' => 'required',
        ]);
});

it('performs service assignment crud operations', function (): void {
    actingAs(makeServiceAssignmentsAdminUser());
    ['request' => $request, 'employee' => $employee] = createServiceAssignmentSetup();

    Livewire::test(CreateServiceAssignment::class)
        ->fillForm([
            'service_request_id' => $request->getKey(),
            'employee_id' => $employee->getKey(),
            'assigned_date' => '2026-07-21',
            'status' => 'assigned',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = ServiceAssignment::query()->firstOrFail();

    Livewire::test(ListServiceAssignments::class)
        ->assertSuccessful()
        ->assertSee('SR-ASSIGN-1');

    Livewire::test(EditServiceAssignment::class, ['record' => $record->getKey()])
        ->fillForm([
            'service_request_id' => $request->getKey(),
            'employee_id' => $employee->getKey(),
            'assigned_date' => '2026-07-22',
            'status' => 'in_progress',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->status)->toBe('in_progress');

    Livewire::test(EditServiceAssignment::class, ['record' => $record->getKey()])->callAction('delete');
    expect(ServiceAssignment::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
