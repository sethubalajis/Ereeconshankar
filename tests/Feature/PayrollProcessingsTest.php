<?php

use App\Filament\Resources\PayrollProcessings\Pages\CreatePayrollProcessing;
use App\Filament\Resources\PayrollProcessings\Pages\EditPayrollProcessing;
use App\Filament\Resources\PayrollProcessings\Pages\ListPayrollProcessings;
use App\Models\Employee;
use App\Models\PayrollCycle;
use App\Models\PayrollProcessing;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makePayrollProcessingsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createPayrollProcessingSetup(): array
{
    $cycle = PayrollCycle::query()->create([
        'name' => 'Payroll Cycle 1',
        'start_date' => '2026-07-01',
        'end_date' => '2026-07-31',
        'payment_date' => '2026-08-05',
        'status' => 'draft',
    ]);

    $employee = Employee::query()->create([
        'first_name' => 'Payroll',
        'last_name' => 'Employee',
        'status' => 'active',
    ]);

    return compact('cycle', 'employee');
}

it('redirects guests away from payroll processings list', function (): void { get('/admin/payroll-processings')->assertRedirect('/admin/login'); });
it('redirects guests away from create payroll processing page', function (): void { get('/admin/payroll-processings/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a payroll processing', function (): void {
    actingAs(makePayrollProcessingsAdminUser());

    Livewire::test(CreatePayrollProcessing::class)
        ->fillForm(['payroll_cycle_id' => null, 'employee_id' => null, 'gross_pay' => null, 'total_deductions' => null, 'net_pay' => null, 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['payroll_cycle_id' => 'required', 'employee_id' => 'required', 'gross_pay' => 'required', 'total_deductions' => 'required', 'net_pay' => 'required', 'status' => 'required']);
});

it('performs payroll processing crud operations', function (): void {
    actingAs(makePayrollProcessingsAdminUser());
    ['cycle' => $cycle, 'employee' => $employee] = createPayrollProcessingSetup();

    Livewire::test(CreatePayrollProcessing::class)
        ->fillForm(['payroll_cycle_id' => $cycle->getKey(), 'employee_id' => $employee->getKey(), 'gross_pay' => 50000, 'total_deductions' => 5000, 'net_pay' => 45000, 'status' => 'processed'])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = PayrollProcessing::query()->firstOrFail();
    Livewire::test(ListPayrollProcessings::class)->assertSuccessful()->assertSee('Payroll');

    Livewire::test(EditPayrollProcessing::class, ['record' => $record->getKey()])
        ->fillForm(['payroll_cycle_id' => $cycle->getKey(), 'employee_id' => $employee->getKey(), 'gross_pay' => 55000, 'total_deductions' => 7000, 'net_pay' => 48000, 'status' => 'approved'])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->status)->toBe('approved');

    Livewire::test(EditPayrollProcessing::class, ['record' => $record->getKey()])->callAction('delete');
    expect(PayrollProcessing::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
