<?php

namespace App\Policies;

use App\Models\AssetCategory;
use App\Models\User;

class AssetCategoryPolicy
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
        return $this->canAny($user, ['asset-categories.view', 'asset_categories.view', 'assetCategory.view', 'asset-category.view', 'view asset categories']);
    }

    public function view(User $user, AssetCategory $record): bool
    {
        return $this->canAny($user, ['asset-categories.view', 'asset_categories.view', 'assetCategory.view', 'asset-category.view', 'view asset categories']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['asset-categories.create', 'asset_categories.create', 'assetCategory.create', 'asset-category.create', 'create asset categories']);
    }

    public function update(User $user, AssetCategory $record): bool
    {
        return $this->canAny($user, ['asset-categories.update', 'asset_categories.update', 'assetCategory.update', 'asset-category.update', 'edit asset categories', 'update asset categories']);
    }

    public function delete(User $user, AssetCategory $record): bool
    {
        return $this->canAny($user, ['asset-categories.delete', 'asset_categories.delete', 'assetCategory.delete', 'asset-category.delete', 'delete asset categories']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['asset-categories.delete', 'asset_categories.delete', 'assetCategory.delete', 'asset-category.delete', 'delete asset categories']);
    }

    public function forceDelete(User $user, AssetCategory $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, AssetCategory $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, AssetCategory $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['asset-categories.update', 'asset_categories.update', 'assetCategory.update', 'asset-category.update', 'edit asset categories', 'update asset categories']);
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