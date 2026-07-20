<?php

namespace App\Policies;

use App\Models\CashOpening;
use App\Models\User;

class CashOpeningPolicy
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
        return $this->canAny($user, ['cash-openings.view', 'cash_openings.view', 'cashOpening.view', 'cash-opening.view', 'view cash openings']);
    }

    public function view(User $user, CashOpening $record): bool
    {
        return $this->canAny($user, ['cash-openings.view', 'cash_openings.view', 'cashOpening.view', 'cash-opening.view', 'view cash openings']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['cash-openings.create', 'cash_openings.create', 'cashOpening.create', 'cash-opening.create', 'create cash openings']);
    }

    public function update(User $user, CashOpening $record): bool
    {
        return $this->canAny($user, ['cash-openings.update', 'cash_openings.update', 'cashOpening.update', 'cash-opening.update', 'edit cash openings', 'update cash openings']);
    }

    public function delete(User $user, CashOpening $record): bool
    {
        return $this->canAny($user, ['cash-openings.delete', 'cash_openings.delete', 'cashOpening.delete', 'cash-opening.delete', 'delete cash openings']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['cash-openings.delete', 'cash_openings.delete', 'cashOpening.delete', 'cash-opening.delete', 'delete cash openings']);
    }

    public function forceDelete(User $user, CashOpening $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, CashOpening $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, CashOpening $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['cash-openings.update', 'cash_openings.update', 'cashOpening.update', 'cash-opening.update', 'edit cash openings', 'update cash openings']);
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