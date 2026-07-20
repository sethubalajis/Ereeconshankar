<?php

namespace App\Policies;

use App\Models\Currency;
use App\Models\User;

class CurrencyPolicy
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
        return $this->canAny($user, ['currencies.view', 'currency.view', 'view currencies']);
    }

    public function view(User $user, Currency $currency): bool
    {
        return $this->canAny($user, ['currencies.view', 'currency.view', 'view currencies']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['currencies.create', 'currency.create', 'create currencies']);
    }

    public function update(User $user, Currency $currency): bool
    {
        return $this->canAny($user, ['currencies.update', 'currency.update', 'edit currencies', 'update currencies']);
    }

    public function delete(User $user, Currency $currency): bool
    {
        return $this->canAny($user, ['currencies.delete', 'currency.delete', 'delete currencies']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['currencies.delete', 'currency.delete', 'delete currencies']);
    }

    public function forceDelete(User $user, Currency $currency): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, Currency $currency): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, Currency $currency): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['currencies.update', 'currency.update', 'edit currencies', 'update currencies']);
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
