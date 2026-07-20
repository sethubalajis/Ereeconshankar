<?php

namespace App\Policies;

use App\Models\Grn;
use App\Models\User;

class GrnPolicy
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
        return $this->canAny($user, ['grns.view', 'grns.view', 'grn.view', 'grn.view', 'view grns']);
    }

    public function view(User $user, Grn $record): bool
    {
        return $this->canAny($user, ['grns.view', 'grns.view', 'grn.view', 'grn.view', 'view grns']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['grns.create', 'grns.create', 'grn.create', 'grn.create', 'create grns']);
    }

    public function update(User $user, Grn $record): bool
    {
        return $this->canAny($user, ['grns.update', 'grns.update', 'grn.update', 'grn.update', 'edit grns', 'update grns']);
    }

    public function delete(User $user, Grn $record): bool
    {
        return $this->canAny($user, ['grns.delete', 'grns.delete', 'grn.delete', 'grn.delete', 'delete grns']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['grns.delete', 'grns.delete', 'grn.delete', 'grn.delete', 'delete grns']);
    }

    public function forceDelete(User $user, Grn $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, Grn $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, Grn $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['grns.update', 'grns.update', 'grn.update', 'grn.update', 'edit grns', 'update grns']);
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