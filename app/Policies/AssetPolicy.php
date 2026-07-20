<?php

namespace App\Policies;

use App\Models\Asset;
use App\Models\User;

class AssetPolicy
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
        return $this->canAny($user, ['assets.view', 'assets.view', 'asset.view', 'asset.view', 'view assets']);
    }

    public function view(User $user, Asset $record): bool
    {
        return $this->canAny($user, ['assets.view', 'assets.view', 'asset.view', 'asset.view', 'view assets']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['assets.create', 'assets.create', 'asset.create', 'asset.create', 'create assets']);
    }

    public function update(User $user, Asset $record): bool
    {
        return $this->canAny($user, ['assets.update', 'assets.update', 'asset.update', 'asset.update', 'edit assets', 'update assets']);
    }

    public function delete(User $user, Asset $record): bool
    {
        return $this->canAny($user, ['assets.delete', 'assets.delete', 'asset.delete', 'asset.delete', 'delete assets']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['assets.delete', 'assets.delete', 'asset.delete', 'asset.delete', 'delete assets']);
    }

    public function forceDelete(User $user, Asset $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, Asset $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, Asset $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['assets.update', 'assets.update', 'asset.update', 'asset.update', 'edit assets', 'update assets']);
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