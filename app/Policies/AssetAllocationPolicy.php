<?php

namespace App\Policies;

use App\Models\AssetAllocation;
use App\Models\User;

class AssetAllocationPolicy
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
        return $this->canAny($user, ['asset-allocations.view', 'asset_allocations.view', 'assetAllocation.view', 'asset-allocation.view', 'view asset allocations']);
    }

    public function view(User $user, AssetAllocation $record): bool
    {
        return $this->canAny($user, ['asset-allocations.view', 'asset_allocations.view', 'assetAllocation.view', 'asset-allocation.view', 'view asset allocations']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['asset-allocations.create', 'asset_allocations.create', 'assetAllocation.create', 'asset-allocation.create', 'create asset allocations']);
    }

    public function update(User $user, AssetAllocation $record): bool
    {
        return $this->canAny($user, ['asset-allocations.update', 'asset_allocations.update', 'assetAllocation.update', 'asset-allocation.update', 'edit asset allocations', 'update asset allocations']);
    }

    public function delete(User $user, AssetAllocation $record): bool
    {
        return $this->canAny($user, ['asset-allocations.delete', 'asset_allocations.delete', 'assetAllocation.delete', 'asset-allocation.delete', 'delete asset allocations']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['asset-allocations.delete', 'asset_allocations.delete', 'assetAllocation.delete', 'asset-allocation.delete', 'delete asset allocations']);
    }

    public function forceDelete(User $user, AssetAllocation $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, AssetAllocation $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, AssetAllocation $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['asset-allocations.update', 'asset_allocations.update', 'assetAllocation.update', 'asset-allocation.update', 'edit asset allocations', 'update asset allocations']);
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