<?php

namespace App\Policies;

use App\Models\AssetTransfer;
use App\Models\User;

class AssetTransferPolicy
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
        return $this->canAny($user, ['asset-transfers.view', 'asset_transfers.view', 'assetTransfer.view', 'asset-transfer.view', 'view asset transfers']);
    }

    public function view(User $user, AssetTransfer $record): bool
    {
        return $this->canAny($user, ['asset-transfers.view', 'asset_transfers.view', 'assetTransfer.view', 'asset-transfer.view', 'view asset transfers']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['asset-transfers.create', 'asset_transfers.create', 'assetTransfer.create', 'asset-transfer.create', 'create asset transfers']);
    }

    public function update(User $user, AssetTransfer $record): bool
    {
        return $this->canAny($user, ['asset-transfers.update', 'asset_transfers.update', 'assetTransfer.update', 'asset-transfer.update', 'edit asset transfers', 'update asset transfers']);
    }

    public function delete(User $user, AssetTransfer $record): bool
    {
        return $this->canAny($user, ['asset-transfers.delete', 'asset_transfers.delete', 'assetTransfer.delete', 'asset-transfer.delete', 'delete asset transfers']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['asset-transfers.delete', 'asset_transfers.delete', 'assetTransfer.delete', 'asset-transfer.delete', 'delete asset transfers']);
    }

    public function forceDelete(User $user, AssetTransfer $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, AssetTransfer $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, AssetTransfer $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['asset-transfers.update', 'asset_transfers.update', 'assetTransfer.update', 'asset-transfer.update', 'edit asset transfers', 'update asset transfers']);
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