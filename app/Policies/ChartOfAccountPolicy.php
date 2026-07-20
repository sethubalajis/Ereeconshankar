<?php

namespace App\Policies;

use App\Models\ChartOfAccount;
use App\Models\User;

class ChartOfAccountPolicy
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
        return $this->canAny($user, ['chart-of-accounts.view', 'chart_of_accounts.view', 'chartOfAccount.view', 'chart-of-account.view', 'view chart of accounts']);
    }

    public function view(User $user, ChartOfAccount $record): bool
    {
        return $this->canAny($user, ['chart-of-accounts.view', 'chart_of_accounts.view', 'chartOfAccount.view', 'chart-of-account.view', 'view chart of accounts']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['chart-of-accounts.create', 'chart_of_accounts.create', 'chartOfAccount.create', 'chart-of-account.create', 'create chart of accounts']);
    }

    public function update(User $user, ChartOfAccount $record): bool
    {
        return $this->canAny($user, ['chart-of-accounts.update', 'chart_of_accounts.update', 'chartOfAccount.update', 'chart-of-account.update', 'edit chart of accounts', 'update chart of accounts']);
    }

    public function delete(User $user, ChartOfAccount $record): bool
    {
        return $this->canAny($user, ['chart-of-accounts.delete', 'chart_of_accounts.delete', 'chartOfAccount.delete', 'chart-of-account.delete', 'delete chart of accounts']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['chart-of-accounts.delete', 'chart_of_accounts.delete', 'chartOfAccount.delete', 'chart-of-account.delete', 'delete chart of accounts']);
    }

    public function forceDelete(User $user, ChartOfAccount $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, ChartOfAccount $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, ChartOfAccount $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['chart-of-accounts.update', 'chart_of_accounts.update', 'chartOfAccount.update', 'chart-of-account.update', 'edit chart of accounts', 'update chart of accounts']);
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