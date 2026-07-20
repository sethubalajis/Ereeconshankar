<?php

namespace App\Policies;

use App\Models\Bin;
use App\Models\User;

class BinPolicy
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
        return $this->canAny($user, ['bins.view', 'bins.view', 'bin.view', 'bin.view', 'view bins']);
    }

    public function view(User $user, Bin $record): bool
    {
        return $this->canAny($user, ['bins.view', 'bins.view', 'bin.view', 'bin.view', 'view bins']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['bins.create', 'bins.create', 'bin.create', 'bin.create', 'create bins']);
    }

    public function update(User $user, Bin $record): bool
    {
        return $this->canAny($user, ['bins.update', 'bins.update', 'bin.update', 'bin.update', 'edit bins', 'update bins']);
    }

    public function delete(User $user, Bin $record): bool
    {
        return $this->canAny($user, ['bins.delete', 'bins.delete', 'bin.delete', 'bin.delete', 'delete bins']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['bins.delete', 'bins.delete', 'bin.delete', 'bin.delete', 'delete bins']);
    }

    public function forceDelete(User $user, Bin $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, Bin $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, Bin $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['bins.update', 'bins.update', 'bin.update', 'bin.update', 'edit bins', 'update bins']);
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