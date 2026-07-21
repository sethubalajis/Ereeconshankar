<?php

use App\Filament\Resources\TechnicianVisits\Pages\CreateTechnicianVisit;
use App\Filament\Resources\TechnicianVisits\Pages\EditTechnicianVisit;
use App\Filament\Resources\TechnicianVisits\Pages\ListTechnicianVisits;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Role;
use App\Models\ServiceAssignment;
use App\Models\ServiceRequest;
use App\Models\TechnicianVisit;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeTechnicianVisitsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

function createTechnicianVisitSetup(): ServiceAssignment
{
    $customer = Customer::query()->create(['name' => 'TV Customer', 'phone' => '9000002201', 'status' => true]);
    $request = ServiceRequest::query()->create([
        'customer_id' => $customer->getKey(),
        'request_number' => 'TV-REQ-1',
        'request_date' => '2026-07-21',
        'status' => 'open',
    ]);
    $employee = Employee::query()->create(['first_name' => 'Tech', 'last_name' => 'One', 'status' => 'active']);

    return ServiceAssignment::query()->create([
        'service_request_id' => $request->getKey(),
        'employee_id' => $employee->getKey(),
        'assigned_date' => '2026-07-21',
        'status' => 'assigned',
    ]);
}

it('redirects guests away from technician visits list', function (): void {
    get('/admin/technician-visits')->assertRedirect('/admin/login');
});

it('redirects guests away from create technician visit page', function (): void {
    get('/admin/technician-visits/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a technician visit', function (): void {
    actingAs(makeTechnicianVisitsAdminUser());

    Livewire::test(CreateTechnicianVisit::class)
        ->fillForm(['service_assignment_id' => null, 'visit_date' => null])
        ->call('create')
        ->assertHasFormErrors(['service_assignment_id' => 'required', 'visit_date' => 'required']);
});

it('performs technician visit crud operations', function (): void {
    actingAs(makeTechnicianVisitsAdminUser());
    $assignment = createTechnicianVisitSetup();

    Livewire::test(CreateTechnicianVisit::class)
        ->fillForm([
            'service_assignment_id' => $assignment->getKey(),
            'visit_date' => '2026-07-21',
            'remarks' => 'Initial inspection completed',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = TechnicianVisit::query()->firstOrFail();
    Livewire::test(ListTechnicianVisits::class)->assertSuccessful()->assertSee('assigned');

    Livewire::test(EditTechnicianVisit::class, ['record' => $record->getKey()])
        ->fillForm([
            'service_assignment_id' => $assignment->getKey(),
            'visit_date' => '2026-07-22',
            'remarks' => 'Replacement done',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect((string) $record->remarks)->toBe('Replacement done');

    Livewire::test(EditTechnicianVisit::class, ['record' => $record->getKey()])->callAction('delete');
    expect(TechnicianVisit::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
