<?php

use App\Filament\Resources\EmployeeExits\Pages\CreateEmployeeExit;
use App\Filament\Resources\EmployeeExits\Pages\EditEmployeeExit;
use App\Filament\Resources\EmployeeExits\Pages\ListEmployeeExits;
use App\Models\Employee;
use App\Models\EmployeeExit;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeEmployeeExitsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createEmployeeExitsEmployee(): Employee
{
    return Employee::query()->create(['first_name' => 'Exit', 'last_name' => 'Employee', 'status' => 'active']);
}

it('redirects guests away from employee exits list', function (): void { get('/admin/employee-exits')->assertRedirect('/admin/login'); });
it('redirects guests away from create employee exit page', function (): void { get('/admin/employee-exits/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating an employee exit', function (): void {
    actingAs(makeEmployeeExitsAdminUser());
    Livewire::test(CreateEmployeeExit::class)
        ->fillForm(['employee_id' => null, 'resignation_date' => null, 'last_working_day' => null, 'exit_type' => null, 'reason' => '', 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['employee_id' => 'required', 'resignation_date' => 'required', 'status' => 'required']);
});

it('performs employee exit crud operations', function (): void {
    actingAs(makeEmployeeExitsAdminUser());
    $employee = createEmployeeExitsEmployee();

    Livewire::test(CreateEmployeeExit::class)
        ->fillForm(['employee_id' => $employee->getKey(), 'resignation_date' => '2026-07-21', 'last_working_day' => '2026-07-31', 'exit_type' => 'resignation', 'reason' => 'Personal', 'status' => 'pending'])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = EmployeeExit::query()->firstOrFail();
    Livewire::test(ListEmployeeExits::class)->assertSuccessful()->assertSee('resignation');

    Livewire::test(EditEmployeeExit::class, ['record' => $record->getKey()])
        ->fillForm(['employee_id' => $employee->getKey(), 'resignation_date' => '2026-07-22', 'last_working_day' => '2026-08-05', 'exit_type' => 'termination', 'reason' => 'Policy issue', 'status' => 'approved'])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->exit_type)->toBe('termination');

    Livewire::test(EditEmployeeExit::class, ['record' => $record->getKey()])->callAction('delete');
    expect(EmployeeExit::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
