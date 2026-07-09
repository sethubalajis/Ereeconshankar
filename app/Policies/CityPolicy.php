<?php

namespace App\Policies;

use App\Models\City;
use App\Models\User;

class CityPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if ($user->hasRole('Super Admin') || $user->hasRole('Admin')) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $this->canAny($user, ['cities.view', 'city.view', 'view cities']);
    }

    public function view(User $user, City $city): bool
    {
        return $this->canAny($user, ['cities.view', 'city.view', 'view cities']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['cities.create', 'city.create', 'create cities']);
    }

    public function update(User $user, City $city): bool
    {
        return $this->canAny($user, ['cities.update', 'city.update', 'edit cities', 'update cities']);
    }

    public function delete(User $user, City $city): bool
    {
        return $this->canAny($user, ['cities.delete', 'city.delete', 'delete cities']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['cities.delete', 'city.delete', 'delete cities']);
    }

    public function forceDelete(User $user, City $city): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, City $city): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, City $city): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['cities.update', 'city.update', 'edit cities', 'update cities']);
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
