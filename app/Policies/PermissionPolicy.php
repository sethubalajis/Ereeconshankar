<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\User;

class PermissionPolicy
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
        return $this->canAny($user, ['permissions.view', 'permissions.view', 'permission.view', 'permission.view', 'view permissions']);
    }

    public function view(User $user, Permission $record): bool
    {
        return $this->canAny($user, ['permissions.view', 'permissions.view', 'permission.view', 'permission.view', 'view permissions']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['permissions.create', 'permissions.create', 'permission.create', 'permission.create', 'create permissions']);
    }

    public function update(User $user, Permission $record): bool
    {
        return $this->canAny($user, ['permissions.update', 'permissions.update', 'permission.update', 'permission.update', 'edit permissions', 'update permissions']);
    }

    public function delete(User $user, Permission $record): bool
    {
        return $this->canAny($user, ['permissions.delete', 'permissions.delete', 'permission.delete', 'permission.delete', 'delete permissions']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['permissions.delete', 'permissions.delete', 'permission.delete', 'permission.delete', 'delete permissions']);
    }

    public function forceDelete(User $user, Permission $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, Permission $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, Permission $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['permissions.update', 'permissions.update', 'permission.update', 'permission.update', 'edit permissions', 'update permissions']);
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