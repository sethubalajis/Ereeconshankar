<?php

use App\Filament\Resources\Departments\Pages\CreateDepartment;
use App\Filament\Resources\Departments\Pages\EditDepartment;
use App\Filament\Resources\Departments\Pages\ListDepartments;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeDepartmentsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createDepartmentBranch(): Branch
{
    $company = Company::query()->create(['name' => 'Dept Co', 'code' => 'DCO001', 'status' => true]);
    return Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'Dept Branch', 'code' => 'DBR001', 'status' => true]);
}

it('redirects guests away from departments list', function (): void { get('/admin/departments')->assertRedirect('/admin/login'); });
it('redirects guests away from create department page', function (): void { get('/admin/departments/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a department', function (): void {
    actingAs(makeDepartmentsAdminUser());
    Livewire::test(CreateDepartment::class)
        ->fillForm(['branch_id' => null, 'name' => '', 'description' => '', 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['branch_id' => 'required', 'name' => 'required', 'status' => 'required']);
});

it('performs department crud operations', function (): void {
    actingAs(makeDepartmentsAdminUser());
    $branch = createDepartmentBranch();

    Livewire::test(CreateDepartment::class)
        ->fillForm(['branch_id' => $branch->getKey(), 'name' => 'HR', 'description' => 'People operations', 'status' => true])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = Department::query()->firstOrFail();
    Livewire::test(ListDepartments::class)->assertSuccessful()->assertSee('HR');

    Livewire::test(EditDepartment::class, ['record' => $record->getKey()])
        ->fillForm(['branch_id' => $branch->getKey(), 'name' => 'Finance', 'description' => 'Accounts', 'status' => false])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->name)->toBe('Finance');

    Livewire::test(EditDepartment::class, ['record' => $record->getKey()])->callAction('delete');
    expect(Department::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
