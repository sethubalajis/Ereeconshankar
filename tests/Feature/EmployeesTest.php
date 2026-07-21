<?php

use App\Filament\Resources\Employees\Pages\CreateEmployee;
use App\Filament\Resources\Employees\Pages\EditEmployee;
use App\Filament\Resources\Employees\Pages\ListEmployees;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeEmployeesAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createEmployeesSetup(): array
{
    $company = Company::query()->create(['name' => 'Emp Co', 'code' => 'ECO001', 'status' => true]);
    $branch = Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'Emp Branch', 'code' => 'EBR001', 'status' => true]);
    $department = Department::query()->create(['branch_id' => $branch->getKey(), 'name' => 'HR', 'status' => true]);
    $designation = Designation::query()->create(['name' => 'Executive', 'status' => true]);
    $user = User::factory()->create();

    return compact('branch', 'department', 'designation', 'user');
}

it('redirects guests away from employees list', function (): void { get('/admin/employees')->assertRedirect('/admin/login'); });
it('redirects guests away from create employee page', function (): void { get('/admin/employees/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating an employee', function (): void {
    actingAs(makeEmployeesAdminUser());
    Livewire::test(CreateEmployee::class)
        ->fillForm(['user_id' => null, 'department_id' => null, 'designation_id' => null, 'branch_id' => null, 'first_name' => '', 'last_name' => '', 'dob' => null, 'joining_date' => null, 'pan_number' => '', 'pf_number' => '', 'pf_opt_in' => true, 'esi_number' => '', 'esi_opt_in' => true, 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['first_name' => 'required', 'status' => 'required']);
});

it('performs employee crud operations', function (): void {
    actingAs(makeEmployeesAdminUser());
    ['branch' => $branch, 'department' => $department, 'designation' => $designation, 'user' => $user] = createEmployeesSetup();

    Livewire::test(CreateEmployee::class)
        ->fillForm(['user_id' => $user->getKey(), 'department_id' => $department->getKey(), 'designation_id' => $designation->getKey(), 'branch_id' => $branch->getKey(), 'first_name' => 'Arun', 'last_name' => 'Kumar', 'dob' => '1994-05-10', 'joining_date' => '2026-07-21', 'pan_number' => 'ABCDE1234F', 'pf_number' => 'PF001', 'pf_opt_in' => true, 'esi_number' => 'ESI001', 'esi_opt_in' => true, 'status' => 'active'])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = Employee::query()->firstOrFail();
    Livewire::test(ListEmployees::class)->assertSuccessful()->assertSee('Arun');

    Livewire::test(EditEmployee::class, ['record' => $record->getKey()])
        ->fillForm(['user_id' => $user->getKey(), 'department_id' => $department->getKey(), 'designation_id' => $designation->getKey(), 'branch_id' => $branch->getKey(), 'first_name' => 'Arun Updated', 'last_name' => 'Kumar', 'dob' => '1994-05-10', 'joining_date' => '2026-07-22', 'pan_number' => 'ABCDE1234F', 'pf_number' => 'PF002', 'pf_opt_in' => false, 'esi_number' => 'ESI002', 'esi_opt_in' => false, 'status' => 'inactive'])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->first_name)->toBe('Arun Updated');

    Livewire::test(EditEmployee::class, ['record' => $record->getKey()])->callAction('delete');
    expect(Employee::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
