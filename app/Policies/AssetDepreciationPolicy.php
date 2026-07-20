<?php

namespace App\Policies;

use App\Models\AssetDepreciation;
use App\Models\User;

class AssetDepreciationPolicy
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
        return $this->canAny($user, ['asset-depreciations.view', 'asset_depreciations.view', 'assetDepreciation.view', 'asset-depreciation.view', 'view asset depreciations']);
    }

    public function view(User $user, AssetDepreciation $record): bool
    {
        return $this->canAny($user, ['asset-depreciations.view', 'asset_depreciations.view', 'assetDepreciation.view', 'asset-depreciation.view', 'view asset depreciations']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['asset-depreciations.create', 'asset_depreciations.create', 'assetDepreciation.create', 'asset-depreciation.create', 'create asset depreciations']);
    }

    public function update(User $user, AssetDepreciation $record): bool
    {
        return $this->canAny($user, ['asset-depreciations.update', 'asset_depreciations.update', 'assetDepreciation.update', 'asset-depreciation.update', 'edit asset depreciations', 'update asset depreciations']);
    }

    public function delete(User $user, AssetDepreciation $record): bool
    {
        return $this->canAny($user, ['asset-depreciations.delete', 'asset_depreciations.delete', 'assetDepreciation.delete', 'asset-depreciation.delete', 'delete asset depreciations']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['asset-depreciations.delete', 'asset_depreciations.delete', 'assetDepreciation.delete', 'asset-depreciation.delete', 'delete asset depreciations']);
    }

    public function forceDelete(User $user, AssetDepreciation $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, AssetDepreciation $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, AssetDepreciation $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['asset-depreciations.update', 'asset_depreciations.update', 'assetDepreciation.update', 'asset-depreciation.update', 'edit asset depreciations', 'update asset depreciations']);
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