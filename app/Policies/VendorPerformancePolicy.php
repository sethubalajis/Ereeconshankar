<?php

namespace App\Policies;

use App\Models\VendorPerformance;
use App\Models\User;

class VendorPerformancePolicy
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
        return $this->canAny($user, ['vendor-performances.view', 'vendor_performances.view', 'vendorPerformance.view', 'vendor-performance.view', 'view vendor performances']);
    }

    public function view(User $user, VendorPerformance $record): bool
    {
        return $this->canAny($user, ['vendor-performances.view', 'vendor_performances.view', 'vendorPerformance.view', 'vendor-performance.view', 'view vendor performances']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['vendor-performances.create', 'vendor_performances.create', 'vendorPerformance.create', 'vendor-performance.create', 'create vendor performances']);
    }

    public function update(User $user, VendorPerformance $record): bool
    {
        return $this->canAny($user, ['vendor-performances.update', 'vendor_performances.update', 'vendorPerformance.update', 'vendor-performance.update', 'edit vendor performances', 'update vendor performances']);
    }

    public function delete(User $user, VendorPerformance $record): bool
    {
        return $this->canAny($user, ['vendor-performances.delete', 'vendor_performances.delete', 'vendorPerformance.delete', 'vendor-performance.delete', 'delete vendor performances']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['vendor-performances.delete', 'vendor_performances.delete', 'vendorPerformance.delete', 'vendor-performance.delete', 'delete vendor performances']);
    }

    public function forceDelete(User $user, VendorPerformance $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, VendorPerformance $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, VendorPerformance $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['vendor-performances.update', 'vendor_performances.update', 'vendorPerformance.update', 'vendor-performance.update', 'edit vendor performances', 'update vendor performances']);
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