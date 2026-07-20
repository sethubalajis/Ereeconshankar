<?php

namespace App\Policies;

use App\Models\AssetDisposal;
use App\Models\User;

class AssetDisposalPolicy
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
        return $this->canAny($user, ['asset-disposals.view', 'asset_disposals.view', 'assetDisposal.view', 'asset-disposal.view', 'view asset disposals']);
    }

    public function view(User $user, AssetDisposal $record): bool
    {
        return $this->canAny($user, ['asset-disposals.view', 'asset_disposals.view', 'assetDisposal.view', 'asset-disposal.view', 'view asset disposals']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['asset-disposals.create', 'asset_disposals.create', 'assetDisposal.create', 'asset-disposal.create', 'create asset disposals']);
    }

    public function update(User $user, AssetDisposal $record): bool
    {
        return $this->canAny($user, ['asset-disposals.update', 'asset_disposals.update', 'assetDisposal.update', 'asset-disposal.update', 'edit asset disposals', 'update asset disposals']);
    }

    public function delete(User $user, AssetDisposal $record): bool
    {
        return $this->canAny($user, ['asset-disposals.delete', 'asset_disposals.delete', 'assetDisposal.delete', 'asset-disposal.delete', 'delete asset disposals']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['asset-disposals.delete', 'asset_disposals.delete', 'assetDisposal.delete', 'asset-disposal.delete', 'delete asset disposals']);
    }

    public function forceDelete(User $user, AssetDisposal $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, AssetDisposal $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, AssetDisposal $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['asset-disposals.update', 'asset_disposals.update', 'assetDisposal.update', 'asset-disposal.update', 'edit asset disposals', 'update asset disposals']);
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