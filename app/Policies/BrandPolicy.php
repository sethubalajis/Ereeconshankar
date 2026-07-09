<?php

namespace App\Policies;

use App\Models\Brand;
use App\Models\User;

class BrandPolicy
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
        return $this->canAny($user, ['brands.view', 'brand.view', 'view brands']);
    }

    public function view(User $user, Brand $brand): bool
    {
        return $this->canAny($user, ['brands.view', 'brand.view', 'view brands']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['brands.create', 'brand.create', 'create brands']);
    }

    public function update(User $user, Brand $brand): bool
    {
        return $this->canAny($user, ['brands.update', 'brand.update', 'edit brands', 'update brands']);
    }

    public function delete(User $user, Brand $brand): bool
    {
        return $this->canAny($user, ['brands.delete', 'brand.delete', 'delete brands']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['brands.delete', 'brand.delete', 'delete brands']);
    }

    public function forceDelete(User $user, Brand $brand): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, Brand $brand): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, Brand $brand): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['brands.update', 'brand.update', 'edit brands', 'update brands']);
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
