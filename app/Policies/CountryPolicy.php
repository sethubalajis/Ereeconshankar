<?php

namespace App\Policies;

use App\Models\Country;
use App\Models\User;

class CountryPolicy
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
        return $this->canAny($user, ['countries.view', 'country.view', 'view countries']);
    }

    public function view(User $user, Country $country): bool
    {
        return $this->canAny($user, ['countries.view', 'country.view', 'view countries']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['countries.create', 'country.create', 'create countries']);
    }

    public function update(User $user, Country $country): bool
    {
        return $this->canAny($user, ['countries.update', 'country.update', 'edit countries', 'update countries']);
    }

    public function delete(User $user, Country $country): bool
    {
        return $this->canAny($user, ['countries.delete', 'country.delete', 'delete countries']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['countries.delete', 'country.delete', 'delete countries']);
    }

    public function forceDelete(User $user, Country $country): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, Country $country): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, Country $country): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['countries.update', 'country.update', 'edit countries', 'update countries']);
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
