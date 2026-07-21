<?php

use App\Filament\Resources\EmployeeLeaves\Pages\CreateEmployeeLeave;
use App\Filament\Resources\EmployeeLeaves\Pages\EditEmployeeLeave;
use App\Filament\Resources\EmployeeLeaves\Pages\ListEmployeeLeaves;
use App\Models\Employee;
use App\Models\EmployeeLeave;
use App\Models\LeaveType;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeEmployeeLeavesAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createEmployeeLeavesEmployee(): Employee
{
    return Employee::query()->create(['first_name' => 'Leave', 'last_name' => 'Employee', 'status' => 'active']);
}

function createEmployeeLeavesType(): LeaveType
{
    return LeaveType::query()->create(['name' => 'Casual Leave', 'days_allowed' => 12, 'status' => true]);
}

it('redirects guests away from employee leaves list', function (): void { get('/admin/employee-leaves')->assertRedirect('/admin/login'); });
it('redirects guests away from create employee leave page', function (): void { get('/admin/employee-leaves/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating an employee leave', function (): void {
    actingAs(makeEmployeeLeavesAdminUser());
    Livewire::test(CreateEmployeeLeave::class)
        ->fillForm(['employee_id' => null, 'leave_type_id' => null, 'start_date' => null, 'end_date' => null, 'status' => null, 'reason' => ''])
        ->call('create')
        ->assertHasFormErrors(['employee_id' => 'required', 'leave_type_id' => 'required', 'start_date' => 'required', 'end_date' => 'required', 'status' => 'required']);
});

it('performs employee leave crud operations', function (): void {
    actingAs(makeEmployeeLeavesAdminUser());
    $employee = createEmployeeLeavesEmployee();
    $leaveType = createEmployeeLeavesType();

    Livewire::test(CreateEmployeeLeave::class)
        ->fillForm(['employee_id' => $employee->getKey(), 'leave_type_id' => $leaveType->getKey(), 'start_date' => '2026-07-21', 'end_date' => '2026-07-23', 'status' => 'pending', 'reason' => 'Family event'])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = EmployeeLeave::query()->firstOrFail();
    Livewire::test(ListEmployeeLeaves::class)->assertSuccessful()->assertSee('pending');

    Livewire::test(EditEmployeeLeave::class, ['record' => $record->getKey()])
        ->fillForm(['employee_id' => $employee->getKey(), 'leave_type_id' => $leaveType->getKey(), 'start_date' => '2026-07-24', 'end_date' => '2026-07-26', 'status' => 'approved', 'reason' => 'Approved leave'])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->status)->toBe('approved');

    Livewire::test(EditEmployeeLeave::class, ['record' => $record->getKey()])->callAction('delete');
    expect(EmployeeLeave::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
