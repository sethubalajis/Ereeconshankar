<?php

use App\Filament\Resources\PayrollProcessings\Pages\CreatePayrollProcessing;
use App\Filament\Resources\PayrollProcessings\Pages\EditPayrollProcessing;
use App\Filament\Resources\PayrollProcessings\Pages\ListPayrollProcessings;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\EmployeeLeave;
use App\Models\EmployeeSalaryAssignment;
use App\Models\LeaveType;
use App\Models\EsiContribution;
use App\Models\TdsRecord;
use App\Models\PayrollCycle;
use App\Models\PayrollProcessing;
use App\Models\Role;
use App\Models\SalaryStructure;
use App\Models\FinancialYear;
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

function createPayrollProcessingSalaryStructure(): SalaryStructure
{
    return SalaryStructure::query()->create([
        'name' => 'Payroll Standard',
        'code' => 'PAY-001',
        'basic_percentage' => 50,
        'hra_percentage' => 20,
        'status' => true,
    ]);
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

it('counts approved leave even when there is no absent attendance row', function (): void {
    actingAs(makePayrollProcessingsAdminUser());
    $cycle = PayrollCycle::query()->create([
        'name' => 'Leave Cycle',
        'start_date' => '2026-07-01',
        'end_date' => '2026-07-03',
        'payment_date' => '2026-08-05',
        'status' => 'draft',
    ]);

    $employee = Employee::query()->create([
        'first_name' => 'Payroll',
        'last_name' => 'Employee',
        'status' => 'active',
    ]);
    $structure = createPayrollProcessingSalaryStructure();

    EmployeeSalaryAssignment::query()->create([
        'employee_id' => $employee->getKey(),
        'salary_structure_id' => $structure->getKey(),
        'gross_salary' => 30000,
        'effective_from' => '2026-07-01',
    ]);

    Attendance::query()->create([
        'employee_id' => $employee->getKey(),
        'date' => '2026-07-01',
        'check_in' => '09:00:00',
        'check_out' => '18:00:00',
        'status' => 'present',
    ]);

    Attendance::query()->create([
        'employee_id' => $employee->getKey(),
        'date' => '2026-07-03',
        'check_in' => '09:00:00',
        'check_out' => '18:00:00',
        'status' => 'present',
    ]);

    $leaveType = LeaveType::query()->create([
        'name' => 'Casual Leave',
        'days_allowed' => 12,
        'status' => true,
    ]);

    EmployeeLeave::query()->create([
        'employee_id' => $employee->getKey(),
        'leave_type_id' => $leaveType->getKey(),
        'start_date' => '2026-07-02',
        'end_date' => '2026-07-02',
        'status' => 'approved',
        'reason' => 'Leave approved',
    ]);

    Livewire::test(CreatePayrollProcessing::class)
        ->fillForm([
            'payroll_cycle_id' => $cycle->getKey(),
            'employee_id' => $employee->getKey(),
        ])
        ->assertFormSet([
            'total_working_days' => 3,
            'effective_present_days' => 3,
            'missing_attendance_days' => 0,
            'absent_leave_days' => 0,
        ]);
});

it('counts missing attendance days as absent leave days', function (): void {
    actingAs(makePayrollProcessingsAdminUser());

    $cycle = PayrollCycle::query()->create([
        'name' => 'Short July Cycle',
        'start_date' => '2026-07-01',
        'end_date' => '2026-07-03',
        'payment_date' => '2026-08-05',
        'status' => 'draft',
    ]);

    $employee = Employee::query()->create([
        'first_name' => 'Missing',
        'last_name' => 'Attendance',
        'status' => 'active',
    ]);

    $structure = createPayrollProcessingSalaryStructure();

    EmployeeSalaryAssignment::query()->create([
        'employee_id' => $employee->getKey(),
        'salary_structure_id' => $structure->getKey(),
        'gross_salary' => 30000,
        'effective_from' => '2026-07-01',
    ]);

    Livewire::test(CreatePayrollProcessing::class)
        ->fillForm([
            'payroll_cycle_id' => $cycle->getKey(),
            'employee_id' => $employee->getKey(),
        ])
        ->assertFormSet([
            'total_working_days' => 3,
            'effective_present_days' => 0,
            'missing_attendance_days' => 3,
            'absent_leave_days' => 3,
        ]);
});

it('computes esi when contribution record is missing but employee is eligible', function (): void {
    actingAs(makePayrollProcessingsAdminUser());

    $cycle = PayrollCycle::query()->create([
        'name' => 'ESI Cycle',
        'start_date' => '2026-07-01',
        'end_date' => '2026-07-03',
        'payment_date' => '2026-08-05',
        'status' => 'draft',
    ]);

    $employee = Employee::query()->create([
        'first_name' => 'ESI',
        'last_name' => 'Fallback',
        'status' => 'active',
        'esi_opt_in' => true,
    ]);

    $structure = createPayrollProcessingSalaryStructure();

    EmployeeSalaryAssignment::query()->create([
        'employee_id' => $employee->getKey(),
        'salary_structure_id' => $structure->getKey(),
        'gross_salary' => 20000,
        'effective_from' => '2026-07-01',
    ]);

    Attendance::query()->create([
        'employee_id' => $employee->getKey(),
        'date' => '2026-07-01',
        'check_in' => '09:00:00',
        'check_out' => '18:00:00',
        'status' => 'present',
    ]);

    Livewire::test(CreatePayrollProcessing::class)
        ->fillForm([
            'payroll_cycle_id' => $cycle->getKey(),
            'employee_id' => $employee->getKey(),
        ])
        ->assertFormSet([
            'esi' => 150,
        ]);
});

it('uses the monthly esi contribution record even when gross salary is above the fallback threshold', function (): void {
    actingAs(makePayrollProcessingsAdminUser());

    $cycle = PayrollCycle::query()->create([
        'name' => 'ESI Record Cycle',
        'start_date' => '2026-07-01',
        'end_date' => '2026-07-31',
        'payment_date' => '2026-08-05',
        'status' => 'draft',
    ]);

    $employee = Employee::query()->create([
        'first_name' => 'ESI',
        'last_name' => 'Record',
        'status' => 'active',
        'esi_opt_in' => true,
    ]);

    $structure = createPayrollProcessingSalaryStructure();

    EmployeeSalaryAssignment::query()->create([
        'employee_id' => $employee->getKey(),
        'salary_structure_id' => $structure->getKey(),
        'gross_salary' => 25000,
        'effective_from' => '2026-07-01',
    ]);

    EsiContribution::query()->create([
        'employee_id' => $employee->getKey(),
        'employee_share' => 222,
        'employer_share' => 888,
        'month' => '2026-07-01',
    ]);

    Attendance::query()->create([
        'employee_id' => $employee->getKey(),
        'date' => '2026-07-01',
        'check_in' => '09:00:00',
        'check_out' => '18:00:00',
        'status' => 'present',
    ]);

    Livewire::test(CreatePayrollProcessing::class)
        ->fillForm([
            'payroll_cycle_id' => $cycle->getKey(),
            'employee_id' => $employee->getKey(),
        ])
        ->assertFormSet([
            'esi' => 222,
        ]);
});

it('uses monthly tds entry for payroll processing instead of slab calculation', function (): void {
    actingAs(makePayrollProcessingsAdminUser());

    $cycle = PayrollCycle::query()->create([
        'name' => 'TDS Cycle',
        'start_date' => '2026-07-01',
        'end_date' => '2026-07-31',
        'payment_date' => '2026-08-05',
        'status' => 'draft',
    ]);

    $employee = Employee::query()->create([
        'first_name' => 'TDS',
        'last_name' => 'Monthly',
        'status' => 'active',
    ]);

    $financialYear = FinancialYear::query()->create([
        'name' => 'FY 2026-2027',
        'start_date' => '2026-04-01',
        'end_date' => '2027-03-31',
        'is_active' => true,
        'is_closed' => false,
    ]);

    $structure = createPayrollProcessingSalaryStructure();

    EmployeeSalaryAssignment::query()->create([
        'employee_id' => $employee->getKey(),
        'salary_structure_id' => $structure->getKey(),
        'gross_salary' => 90000,
        'effective_from' => '2026-07-01',
    ]);

    TdsRecord::query()->create([
        'employee_id' => $employee->getKey(),
        'financial_year_id' => $financialYear->getKey(),
        'tax_regime' => 'old',
        'annual_gross_salary' => 1080000,
        'approved_tax_deductions' => 0,
        'taxable_income' => 1080000,
        'annual_income_tax' => 99999,
        'monthly_tds' => 1234.56,
        'remaining_tds' => 0,
        'status' => 'processed',
        'created_at' => '2026-07-10 10:00:00',
        'updated_at' => '2026-07-10 10:00:00',
    ]);

    Livewire::test(CreatePayrollProcessing::class)
        ->fillForm([
            'payroll_cycle_id' => $cycle->getKey(),
            'employee_id' => $employee->getKey(),
        ])
        ->assertFormSet([
            'tds_records' => 1234.56,
        ]);
});
