<?php

use App\Filament\Resources\EmployeeTransfers\Pages\CreateEmployeeTransfer;
use App\Filament\Resources\EmployeeTransfers\Pages\EditEmployeeTransfer;
use App\Filament\Resources\EmployeeTransfers\Pages\ListEmployeeTransfers;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Employee;
use App\Models\EmployeeTransfer;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeEmployeeTransfersAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createEmployeeTransferBranches(): array
{
    $company = Company::query()->create(['name' => 'Transfer Co', 'code' => 'TRC001', 'status' => true]);
    $fromBranch = Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'From Branch', 'code' => 'FBR001', 'status' => true]);
    $toBranch = Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'To Branch', 'code' => 'TBR001', 'status' => true]);
    return compact('fromBranch', 'toBranch');
}

function createEmployeeTransferEmployee(): Employee
{
    return Employee::query()->create(['first_name' => 'Transfer', 'last_name' => 'Employee', 'status' => 'active']);
}

it('redirects guests away from employee transfers list', function (): void { get('/admin/employee-transfers')->assertRedirect('/admin/login'); });
it('redirects guests away from create employee transfer page', function (): void { get('/admin/employee-transfers/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating an employee transfer', function (): void {
    actingAs(makeEmployeeTransfersAdminUser());
    Livewire::test(CreateEmployeeTransfer::class)
        ->fillForm(['employee_id' => null, 'from_branch_id' => null, 'to_branch_id' => null, 'transfer_date' => null, 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['employee_id' => 'required', 'from_branch_id' => 'required', 'to_branch_id' => 'required', 'transfer_date' => 'required', 'status' => 'required']);
});

it('performs employee transfer crud operations', function (): void {
    actingAs(makeEmployeeTransfersAdminUser());
    $employee = createEmployeeTransferEmployee();
    ['fromBranch' => $fromBranch, 'toBranch' => $toBranch] = createEmployeeTransferBranches();

    Livewire::test(CreateEmployeeTransfer::class)
        ->fillForm(['employee_id' => $employee->getKey(), 'from_branch_id' => $fromBranch->getKey(), 'to_branch_id' => $toBranch->getKey(), 'transfer_date' => '2026-07-21', 'status' => 'pending'])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = EmployeeTransfer::query()->firstOrFail();
    Livewire::test(ListEmployeeTransfers::class)->assertSuccessful()->assertSee('pending');

    Livewire::test(EditEmployeeTransfer::class, ['record' => $record->getKey()])
        ->fillForm(['employee_id' => $employee->getKey(), 'from_branch_id' => $fromBranch->getKey(), 'to_branch_id' => $toBranch->getKey(), 'transfer_date' => '2026-08-01', 'status' => 'approved'])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->status)->toBe('approved');

    Livewire::test(EditEmployeeTransfer::class, ['record' => $record->getKey()])->callAction('delete');
    expect(EmployeeTransfer::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
