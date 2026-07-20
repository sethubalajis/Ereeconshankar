<?php

namespace App\Policies;

use App\Models\CashRegister;
use App\Models\User;

class CashRegisterPolicy
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
        return $this->canAny($user, ['cash-registers.view', 'cash_registers.view', 'cashRegister.view', 'cash-register.view', 'view cash registers']);
    }

    public function view(User $user, CashRegister $record): bool
    {
        return $this->canAny($user, ['cash-registers.view', 'cash_registers.view', 'cashRegister.view', 'cash-register.view', 'view cash registers']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['cash-registers.create', 'cash_registers.create', 'cashRegister.create', 'cash-register.create', 'create cash registers']);
    }

    public function update(User $user, CashRegister $record): bool
    {
        return $this->canAny($user, ['cash-registers.update', 'cash_registers.update', 'cashRegister.update', 'cash-register.update', 'edit cash registers', 'update cash registers']);
    }

    public function delete(User $user, CashRegister $record): bool
    {
        return $this->canAny($user, ['cash-registers.delete', 'cash_registers.delete', 'cashRegister.delete', 'cash-register.delete', 'delete cash registers']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['cash-registers.delete', 'cash_registers.delete', 'cashRegister.delete', 'cash-register.delete', 'delete cash registers']);
    }

    public function forceDelete(User $user, CashRegister $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, CashRegister $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, CashRegister $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['cash-registers.update', 'cash_registers.update', 'cashRegister.update', 'cash-register.update', 'edit cash registers', 'update cash registers']);
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