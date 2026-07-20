<?php

namespace App\Policies;

use App\Models\BankAccount;
use App\Models\User;

class BankAccountPolicy
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
        return $this->canAny($user, ['bank-accounts.view', 'bank_accounts.view', 'bankAccount.view', 'bank-account.view', 'view bank accounts']);
    }

    public function view(User $user, BankAccount $record): bool
    {
        return $this->canAny($user, ['bank-accounts.view', 'bank_accounts.view', 'bankAccount.view', 'bank-account.view', 'view bank accounts']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['bank-accounts.create', 'bank_accounts.create', 'bankAccount.create', 'bank-account.create', 'create bank accounts']);
    }

    public function update(User $user, BankAccount $record): bool
    {
        return $this->canAny($user, ['bank-accounts.update', 'bank_accounts.update', 'bankAccount.update', 'bank-account.update', 'edit bank accounts', 'update bank accounts']);
    }

    public function delete(User $user, BankAccount $record): bool
    {
        return $this->canAny($user, ['bank-accounts.delete', 'bank_accounts.delete', 'bankAccount.delete', 'bank-account.delete', 'delete bank accounts']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['bank-accounts.delete', 'bank_accounts.delete', 'bankAccount.delete', 'bank-account.delete', 'delete bank accounts']);
    }

    public function forceDelete(User $user, BankAccount $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, BankAccount $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, BankAccount $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['bank-accounts.update', 'bank_accounts.update', 'bankAccount.update', 'bank-account.update', 'edit bank accounts', 'update bank accounts']);
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