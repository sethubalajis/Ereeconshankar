<?php

namespace App\Policies;

use App\Models\Vendor;
use App\Models\User;

class VendorPolicy
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
        return $this->canAny($user, ['vendors.view', 'vendors.view', 'vendor.view', 'vendor.view', 'view vendors']);
    }

    public function view(User $user, Vendor $record): bool
    {
        return $this->canAny($user, ['vendors.view', 'vendors.view', 'vendor.view', 'vendor.view', 'view vendors']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['vendors.create', 'vendors.create', 'vendor.create', 'vendor.create', 'create vendors']);
    }

    public function update(User $user, Vendor $record): bool
    {
        return $this->canAny($user, ['vendors.update', 'vendors.update', 'vendor.update', 'vendor.update', 'edit vendors', 'update vendors']);
    }

    public function delete(User $user, Vendor $record): bool
    {
        return $this->canAny($user, ['vendors.delete', 'vendors.delete', 'vendor.delete', 'vendor.delete', 'delete vendors']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['vendors.delete', 'vendors.delete', 'vendor.delete', 'vendor.delete', 'delete vendors']);
    }

    public function forceDelete(User $user, Vendor $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, Vendor $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, Vendor $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['vendors.update', 'vendors.update', 'vendor.update', 'vendor.update', 'edit vendors', 'update vendors']);
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