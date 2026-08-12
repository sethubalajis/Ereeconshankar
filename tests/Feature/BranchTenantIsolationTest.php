<?php

use App\Filament\Branch\Resources\BranchExpenses\BranchExpenseResource;
use App\Models\Branch;
use App\Models\BranchExpense;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use App\Models\UserBranchAccess;
use function Pest\Laravel\actingAs;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function createBranchTenantSetup(): array
{
    $company = Company::query()->create([
        'name' => 'Tenant Isolation Co',
        'code' => 'TISO1',
        'status' => true,
    ]);

    $branchA = Branch::query()->create([
        'company_id' => $company->getKey(),
        'name' => 'Salem Main Showroom',
        'code' => 'SMS001',
        'status' => true,
    ]);

    $branchB = Branch::query()->create([
        'company_id' => $company->getKey(),
        'name' => 'Erode Showroom',
        'code' => 'ERS001',
        'status' => true,
    ]);

    return compact('branchA', 'branchB');
}

it('returns only mapped branch records for branch resource queries', function (): void {
    ['branchA' => $branchA, 'branchB' => $branchB] = createBranchTenantSetup();

    $user = User::factory()->create();
    $role = Role::query()->firstOrCreate(['name' => 'Cashier', 'guard_name' => 'web']);
    $user->assignRole($role);

    UserBranchAccess::query()->create([
        'user_id' => $user->getKey(),
        'branch_id' => $branchA->getKey(),
        'is_default' => true,
    ]);

    $ownRecord = BranchExpense::query()->create([
        'branch_id' => $branchA->getKey(),
        'expense_date' => '2026-08-13',
        'amount' => 1200,
        'description' => 'Own branch expense',
        'status' => 'approved',
    ]);

    $otherRecord = BranchExpense::query()->create([
        'branch_id' => $branchB->getKey(),
        'expense_date' => '2026-08-13',
        'amount' => 2400,
        'description' => 'Other branch expense',
        'status' => 'approved',
    ]);

    actingAs($user);

    $visibleIds = BranchExpenseResource::getEloquentQuery()
        ->pluck('id')
        ->all();

    expect($visibleIds)
        ->toContain($ownRecord->getKey())
        ->not->toContain($otherRecord->getKey());
});

it('returns no branch resource records when user has no branch mapping', function (): void {
    ['branchA' => $branchA, 'branchB' => $branchB] = createBranchTenantSetup();

    BranchExpense::query()->create([
        'branch_id' => $branchA->getKey(),
        'expense_date' => '2026-08-13',
        'amount' => 500,
        'description' => 'Branch A expense',
        'status' => 'approved',
    ]);

    BranchExpense::query()->create([
        'branch_id' => $branchB->getKey(),
        'expense_date' => '2026-08-13',
        'amount' => 900,
        'description' => 'Branch B expense',
        'status' => 'approved',
    ]);

    $user = User::factory()->create();
    $role = Role::query()->firstOrCreate(['name' => 'Cashier', 'guard_name' => 'web']);
    $user->assignRole($role);

    actingAs($user);

    $visibleCount = BranchExpenseResource::getEloquentQuery()->count();

    expect($visibleCount)->toBe(0);
});
