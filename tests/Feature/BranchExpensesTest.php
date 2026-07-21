<?php

use App\Filament\Resources\BranchExpenses\Pages\CreateBranchExpense;
use App\Filament\Resources\BranchExpenses\Pages\EditBranchExpense;
use App\Filament\Resources\BranchExpenses\Pages\ListBranchExpenses;
use App\Models\Branch;
use App\Models\BranchExpense;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeBranchExpensesAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createBranchExpenseBranch(): Branch
{
    $company = Company::query()->create(['name' => 'Branch Expense Co', 'code' => 'BEC001', 'status' => true]);
    return Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'Expense Branch', 'code' => 'EBR001', 'status' => true]);
}

it('redirects guests away from branch expenses list', function (): void { get('/admin/branch-expenses')->assertRedirect('/admin/login'); });
it('redirects guests away from create branch expense page', function (): void { get('/admin/branch-expenses/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a branch expense', function (): void {
    actingAs(makeBranchExpensesAdminUser());
    Livewire::test(CreateBranchExpense::class)
        ->fillForm(['branch_id' => null, 'expense_date' => null, 'amount' => null, 'description' => '', 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['branch_id' => 'required', 'expense_date' => 'required', 'amount' => 'required', 'status' => 'required']);
});

it('performs branch expense crud operations', function (): void {
    actingAs(makeBranchExpensesAdminUser());
    $branch = createBranchExpenseBranch();

    Livewire::test(CreateBranchExpense::class)
        ->fillForm(['branch_id' => $branch->getKey(), 'expense_date' => '2026-07-21', 'amount' => 250, 'description' => 'Repairs', 'status' => 'pending'])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = BranchExpense::query()->firstOrFail();
    Livewire::test(ListBranchExpenses::class)->assertSuccessful()->assertSee('250');

    Livewire::test(EditBranchExpense::class, ['record' => $record->getKey()])
        ->fillForm(['branch_id' => $branch->getKey(), 'expense_date' => '2026-07-22', 'amount' => 300, 'description' => 'Maintenance', 'status' => 'approved'])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect((float) $record->amount)->toBe(300.0);

    Livewire::test(EditBranchExpense::class, ['record' => $record->getKey()])->callAction('delete');
    expect(BranchExpense::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
