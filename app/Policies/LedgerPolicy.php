<?php

namespace App\Policies;

use App\Models\Ledger;
use App\Models\User;

class LedgerPolicy
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
        return $this->canAny($user, ['ledgers.view', 'ledgers.view', 'ledger.view', 'ledger.view', 'view ledgers']);
    }

    public function view(User $user, Ledger $record): bool
    {
        return $this->canAny($user, ['ledgers.view', 'ledgers.view', 'ledger.view', 'ledger.view', 'view ledgers']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['ledgers.create', 'ledgers.create', 'ledger.create', 'ledger.create', 'create ledgers']);
    }

    public function update(User $user, Ledger $record): bool
    {
        return $this->canAny($user, ['ledgers.update', 'ledgers.update', 'ledger.update', 'ledger.update', 'edit ledgers', 'update ledgers']);
    }

    public function delete(User $user, Ledger $record): bool
    {
        return $this->canAny($user, ['ledgers.delete', 'ledgers.delete', 'ledger.delete', 'ledger.delete', 'delete ledgers']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['ledgers.delete', 'ledgers.delete', 'ledger.delete', 'ledger.delete', 'delete ledgers']);
    }

    public function forceDelete(User $user, Ledger $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, Ledger $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, Ledger $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['ledgers.update', 'ledgers.update', 'ledger.update', 'ledger.update', 'edit ledgers', 'update ledgers']);
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