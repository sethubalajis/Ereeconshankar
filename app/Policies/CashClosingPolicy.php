<?php

namespace App\Policies;

use App\Models\CashClosing;
use App\Models\User;

class CashClosingPolicy
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
        return $this->canAny($user, ['cash-closings.view', 'cash_closings.view', 'cashClosing.view', 'cash-closing.view', 'view cash closings']);
    }

    public function view(User $user, CashClosing $record): bool
    {
        return $this->canAny($user, ['cash-closings.view', 'cash_closings.view', 'cashClosing.view', 'cash-closing.view', 'view cash closings']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['cash-closings.create', 'cash_closings.create', 'cashClosing.create', 'cash-closing.create', 'create cash closings']);
    }

    public function update(User $user, CashClosing $record): bool
    {
        return $this->canAny($user, ['cash-closings.update', 'cash_closings.update', 'cashClosing.update', 'cash-closing.update', 'edit cash closings', 'update cash closings']);
    }

    public function delete(User $user, CashClosing $record): bool
    {
        return $this->canAny($user, ['cash-closings.delete', 'cash_closings.delete', 'cashClosing.delete', 'cash-closing.delete', 'delete cash closings']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['cash-closings.delete', 'cash_closings.delete', 'cashClosing.delete', 'cash-closing.delete', 'delete cash closings']);
    }

    public function forceDelete(User $user, CashClosing $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, CashClosing $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, CashClosing $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['cash-closings.update', 'cash_closings.update', 'cashClosing.update', 'cash-closing.update', 'edit cash closings', 'update cash closings']);
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