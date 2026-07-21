<?php

use App\Filament\Resources\Deductions\Pages\CreateDeduction;
use App\Filament\Resources\Deductions\Pages\EditDeduction;
use App\Filament\Resources\Deductions\Pages\ListDeductions;
use App\Models\Deduction;
use App\Models\Employee;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeDeductionsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createDeductionEmployee(): Employee
{
    return Employee::query()->create(['first_name' => 'Deduct', 'last_name' => 'Employee', 'status' => 'active']);
}

it('redirects guests away from deductions list', function (): void { get('/admin/deductions')->assertRedirect('/admin/login'); });
it('redirects guests away from create deduction page', function (): void { get('/admin/deductions/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a deduction', function (): void {
    actingAs(makeDeductionsAdminUser());
    Livewire::test(CreateDeduction::class)
        ->fillForm(['employee_id' => null, 'deduction_type' => null, 'amount' => null, 'month' => null])
        ->call('create')
        ->assertHasFormErrors(['employee_id' => 'required', 'deduction_type' => 'required', 'amount' => 'required', 'month' => 'required']);
});

it('performs deduction crud operations', function (): void {
    actingAs(makeDeductionsAdminUser());
    $employee = createDeductionEmployee();

    Livewire::test(CreateDeduction::class)
        ->fillForm(['employee_id' => $employee->getKey(), 'deduction_type' => 'tax', 'amount' => 500, 'month' => '2026-07-01'])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = Deduction::query()->firstOrFail();
    Livewire::test(ListDeductions::class)->assertSuccessful()->assertSee('tax');

    Livewire::test(EditDeduction::class, ['record' => $record->getKey()])
        ->fillForm(['employee_id' => $employee->getKey(), 'deduction_type' => 'loan_repayment', 'amount' => 700, 'month' => '2026-08-01'])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->deduction_type)->toBe('loan_repayment');

    Livewire::test(EditDeduction::class, ['record' => $record->getKey()])->callAction('delete');
    expect(Deduction::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
