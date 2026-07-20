<?php

namespace App\Policies;

use App\Models\VendorCategory;
use App\Models\User;

class VendorCategoryPolicy
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
        return $this->canAny($user, ['vendor-categories.view', 'vendor_categories.view', 'vendorCategory.view', 'vendor-category.view', 'view vendor categories']);
    }

    public function view(User $user, VendorCategory $record): bool
    {
        return $this->canAny($user, ['vendor-categories.view', 'vendor_categories.view', 'vendorCategory.view', 'vendor-category.view', 'view vendor categories']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['vendor-categories.create', 'vendor_categories.create', 'vendorCategory.create', 'vendor-category.create', 'create vendor categories']);
    }

    public function update(User $user, VendorCategory $record): bool
    {
        return $this->canAny($user, ['vendor-categories.update', 'vendor_categories.update', 'vendorCategory.update', 'vendor-category.update', 'edit vendor categories', 'update vendor categories']);
    }

    public function delete(User $user, VendorCategory $record): bool
    {
        return $this->canAny($user, ['vendor-categories.delete', 'vendor_categories.delete', 'vendorCategory.delete', 'vendor-category.delete', 'delete vendor categories']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['vendor-categories.delete', 'vendor_categories.delete', 'vendorCategory.delete', 'vendor-category.delete', 'delete vendor categories']);
    }

    public function forceDelete(User $user, VendorCategory $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, VendorCategory $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, VendorCategory $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['vendor-categories.update', 'vendor_categories.update', 'vendorCategory.update', 'vendor-category.update', 'edit vendor categories', 'update vendor categories']);
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