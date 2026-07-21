<?php

use App\Filament\Resources\EmployeeSalaryAssignments\Pages\CreateEmployeeSalaryAssignment;
use App\Filament\Resources\EmployeeSalaryAssignments\Pages\EditEmployeeSalaryAssignment;
use App\Filament\Resources\EmployeeSalaryAssignments\Pages\ListEmployeeSalaryAssignments;
use App\Models\Employee;
use App\Models\EmployeeSalaryAssignment;
use App\Models\Role;
use App\Models\SalaryStructure;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeEmployeeSalaryAssignmentsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createSalaryAssignmentEmployee(): Employee
{
    return Employee::query()->create(['first_name' => 'Salary', 'last_name' => 'Employee', 'status' => 'active']);
}

function createSalaryStructure(): SalaryStructure
{
    return SalaryStructure::query()->create(['name' => 'Standard', 'basic_percentage' => 50, 'hra_percentage' => 20, 'status' => true]);
}

it('redirects guests away from employee salary assignments list', function (): void { get('/admin/employee-salary-assignments')->assertRedirect('/admin/login'); });
it('redirects guests away from create employee salary assignment page', function (): void { get('/admin/employee-salary-assignments/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating an employee salary assignment', function (): void {
    actingAs(makeEmployeeSalaryAssignmentsAdminUser());
    Livewire::test(CreateEmployeeSalaryAssignment::class)
        ->fillForm(['employee_id' => null, 'salary_structure_id' => null, 'gross_salary' => null, 'effective_from' => null])
        ->call('create')
        ->assertHasFormErrors(['employee_id' => 'required', 'salary_structure_id' => 'required', 'gross_salary' => 'required', 'effective_from' => 'required']);
});

it('performs employee salary assignment crud operations', function (): void {
    actingAs(makeEmployeeSalaryAssignmentsAdminUser());
    $employee = createSalaryAssignmentEmployee();
    $structure = createSalaryStructure();

    Livewire::test(CreateEmployeeSalaryAssignment::class)
        ->fillForm(['employee_id' => $employee->getKey(), 'salary_structure_id' => $structure->getKey(), 'gross_salary' => 35000, 'effective_from' => '2026-07-21'])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = EmployeeSalaryAssignment::query()->firstOrFail();
    Livewire::test(ListEmployeeSalaryAssignments::class)->assertSuccessful()->assertSee('35000');

    Livewire::test(EditEmployeeSalaryAssignment::class, ['record' => $record->getKey()])
        ->fillForm(['employee_id' => $employee->getKey(), 'salary_structure_id' => $structure->getKey(), 'gross_salary' => 40000, 'effective_from' => '2026-08-01'])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect((float) $record->gross_salary)->toBe(40000.0);

    Livewire::test(EditEmployeeSalaryAssignment::class, ['record' => $record->getKey()])->callAction('delete');
    expect(EmployeeSalaryAssignment::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
