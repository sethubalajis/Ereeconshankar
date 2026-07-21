<?php

use App\Filament\Resources\Reimbursements\Pages\CreateReimbursement;
use App\Filament\Resources\Reimbursements\Pages\EditReimbursement;
use App\Filament\Resources\Reimbursements\Pages\ListReimbursements;
use App\Models\Employee;
use App\Models\Reimbursement;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeReimbursementsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

function createReimbursementEmployee(): Employee
{
    return Employee::query()->create([
        'first_name' => 'Reim',
        'last_name' => 'User',
        'status' => 'active',
    ]);
}

it('redirects guests away from reimbursements list', function (): void {
    get('/admin/reimbursements')->assertRedirect('/admin/login');
});

it('redirects guests away from create reimbursement page', function (): void {
    get('/admin/reimbursements/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a reimbursement', function (): void {
    actingAs(makeReimbursementsAdminUser());

    Livewire::test(CreateReimbursement::class)
        ->fillForm([
            'employee_id' => null,
            'expense_date' => null,
            'amount' => null,
            'status' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'employee_id' => 'required',
            'expense_date' => 'required',
            'amount' => 'required',
            'status' => 'required',
        ]);
});

it('performs reimbursement crud operations', function (): void {
    actingAs(makeReimbursementsAdminUser());
    $employee = createReimbursementEmployee();

    Livewire::test(CreateReimbursement::class)
        ->fillForm([
            'employee_id' => $employee->getKey(),
            'expense_date' => '2026-07-21',
            'amount' => 1200,
            'status' => 'pending',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = Reimbursement::query()->firstOrFail();

    Livewire::test(ListReimbursements::class)
        ->assertSuccessful()
        ->assertSee('Reim');

    Livewire::test(EditReimbursement::class, ['record' => $record->getKey()])
        ->fillForm([
            'employee_id' => $employee->getKey(),
            'expense_date' => '2026-07-22',
            'amount' => 1500,
            'status' => 'approved',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect((float) $record->amount)->toBe(1500.0);

    Livewire::test(EditReimbursement::class, ['record' => $record->getKey()])->callAction('delete');
    expect(Reimbursement::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
