<?php

namespace App\Policies;

use App\Models\BranchExpense;
use App\Models\User;

class BranchExpensePolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $this->canAny($user, ['branch-expenses.view', 'branch_expenses.view', 'branchExpense.view', 'branch-expense.view', 'view branch expenses']);
    }

    public function view(User $user, BranchExpense $record): bool
    {
        return $this->canAny($user, ['branch-expenses.view', 'branch_expenses.view', 'branchExpense.view', 'branch-expense.view', 'view branch expenses']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['branch-expenses.create', 'branch_expenses.create', 'branchExpense.create', 'branch-expense.create', 'create branch expenses']);
    }

    public function update(User $user, BranchExpense $record): bool
    {
        return $this->canAny($user, ['branch-expenses.update', 'branch_expenses.update', 'branchExpense.update', 'branch-expense.update', 'edit branch expenses', 'update branch expenses']);
    }

    public function delete(User $user, BranchExpense $record): bool
    {
        return $this->canAny($user, ['branch-expenses.delete', 'branch_expenses.delete', 'branchExpense.delete', 'branch-expense.delete', 'delete branch expenses']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['branch-expenses.delete', 'branch_expenses.delete', 'branchExpense.delete', 'branch-expense.delete', 'delete branch expenses']);
    }

    public function forceDelete(User $user, BranchExpense $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, BranchExpense $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, BranchExpense $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['branch-expenses.update', 'branch_expenses.update', 'branchExpense.update', 'branch-expense.update', 'edit branch expenses', 'update branch expenses']);
    }

    private function canAny(User $user, array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($user->can($permission)) {
                return true;
            }
        }

        return false;
    }
}