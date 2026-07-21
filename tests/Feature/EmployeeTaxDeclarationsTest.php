<?php

use App\Filament\Resources\EmployeeTaxDeclarations\Pages\CreateEmployeeTaxDeclaration;
use App\Filament\Resources\EmployeeTaxDeclarations\Pages\EditEmployeeTaxDeclaration;
use App\Filament\Resources\EmployeeTaxDeclarations\Pages\ListEmployeeTaxDeclarations;
use App\Models\Employee;
use App\Models\EmployeeTaxDeclaration;
use App\Models\FinancialYear;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeEmployeeTaxDeclarationsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createTaxDeclarationEmployee(): Employee
{
    return Employee::query()->create(['first_name' => 'Tax', 'last_name' => 'Employee', 'status' => 'active']);
}

function createTaxDeclarationFinancialYear(): FinancialYear
{
    return FinancialYear::query()->create(['name' => 'FY 2026-27', 'start_date' => '2026-04-01', 'end_date' => '2027-03-31', 'is_active' => true, 'is_closed' => false]);
}

it('redirects guests away from employee tax declarations list', function (): void { get('/admin/employee-tax-declarations')->assertRedirect('/admin/login'); });
it('redirects guests away from create employee tax declaration page', function (): void { get('/admin/employee-tax-declarations/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating an employee tax declaration', function (): void {
    actingAs(makeEmployeeTaxDeclarationsAdminUser());
    Livewire::test(CreateEmployeeTaxDeclaration::class)
        ->fillForm(['employee_id' => null, 'financial_year_id' => null, 'tax_regime' => null, 'declaration_date' => null, 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['employee_id' => 'required', 'financial_year_id' => 'required', 'tax_regime' => 'required', 'declaration_date' => 'required', 'status' => 'required']);
});

it('performs employee tax declaration crud operations', function (): void {
    actingAs(makeEmployeeTaxDeclarationsAdminUser());
    $employee = createTaxDeclarationEmployee();
    $financialYear = createTaxDeclarationFinancialYear();

    Livewire::test(CreateEmployeeTaxDeclaration::class)
        ->fillForm(['employee_id' => $employee->getKey(), 'financial_year_id' => $financialYear->getKey(), 'tax_regime' => 'old', 'declaration_date' => '2026-07-21', 'status' => 'draft', 'remarks' => 'Initial declaration'])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = EmployeeTaxDeclaration::query()->firstOrFail();
    Livewire::test(ListEmployeeTaxDeclarations::class)->assertSuccessful()->assertSee('old');

    Livewire::test(EditEmployeeTaxDeclaration::class, ['record' => $record->getKey()])
        ->fillForm(['employee_id' => $employee->getKey(), 'financial_year_id' => $financialYear->getKey(), 'tax_regime' => 'new', 'declaration_date' => '2026-08-01', 'status' => 'submitted', 'remarks' => 'Submitted declaration'])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->tax_regime)->toBe('new');

    Livewire::test(EditEmployeeTaxDeclaration::class, ['record' => $record->getKey()])->callAction('delete');
    expect(EmployeeTaxDeclaration::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
