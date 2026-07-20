<?php

namespace App\Policies;

use App\Models\AssetMaintenance;
use App\Models\User;

class AssetMaintenancePolicy
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
        return $this->canAny($user, ['asset-maintenances.view', 'asset_maintenances.view', 'assetMaintenance.view', 'asset-maintenance.view', 'view asset maintenances']);
    }

    public function view(User $user, AssetMaintenance $record): bool
    {
        return $this->canAny($user, ['asset-maintenances.view', 'asset_maintenances.view', 'assetMaintenance.view', 'asset-maintenance.view', 'view asset maintenances']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['asset-maintenances.create', 'asset_maintenances.create', 'assetMaintenance.create', 'asset-maintenance.create', 'create asset maintenances']);
    }

    public function update(User $user, AssetMaintenance $record): bool
    {
        return $this->canAny($user, ['asset-maintenances.update', 'asset_maintenances.update', 'assetMaintenance.update', 'asset-maintenance.update', 'edit asset maintenances', 'update asset maintenances']);
    }

    public function delete(User $user, AssetMaintenance $record): bool
    {
        return $this->canAny($user, ['asset-maintenances.delete', 'asset_maintenances.delete', 'assetMaintenance.delete', 'asset-maintenance.delete', 'delete asset maintenances']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['asset-maintenances.delete', 'asset_maintenances.delete', 'assetMaintenance.delete', 'asset-maintenance.delete', 'delete asset maintenances']);
    }

    public function forceDelete(User $user, AssetMaintenance $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, AssetMaintenance $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, AssetMaintenance $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['asset-maintenances.update', 'asset_maintenances.update', 'assetMaintenance.update', 'asset-maintenance.update', 'edit asset maintenances', 'update asset maintenances']);
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