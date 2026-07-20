<?php

namespace App\Policies;

use App\Models\RolePermission;
use App\Models\User;

class RolePermissionPolicy
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
        return $this->canAny($user, ['role-permissions.view', 'role_permissions.view', 'rolePermission.view', 'role-permission.view', 'view role permissions']);
    }

    public function view(User $user, RolePermission $record): bool
    {
        return $this->canAny($user, ['role-permissions.view', 'role_permissions.view', 'rolePermission.view', 'role-permission.view', 'view role permissions']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['role-permissions.create', 'role_permissions.create', 'rolePermission.create', 'role-permission.create', 'create role permissions']);
    }

    public function update(User $user, RolePermission $record): bool
    {
        return $this->canAny($user, ['role-permissions.update', 'role_permissions.update', 'rolePermission.update', 'role-permission.update', 'edit role permissions', 'update role permissions']);
    }

    public function delete(User $user, RolePermission $record): bool
    {
        return $this->canAny($user, ['role-permissions.delete', 'role_permissions.delete', 'rolePermission.delete', 'role-permission.delete', 'delete role permissions']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['role-permissions.delete', 'role_permissions.delete', 'rolePermission.delete', 'role-permission.delete', 'delete role permissions']);
    }

    public function forceDelete(User $user, RolePermission $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, RolePermission $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, RolePermission $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['role-permissions.update', 'role_permissions.update', 'rolePermission.update', 'role-permission.update', 'edit role permissions', 'update role permissions']);
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