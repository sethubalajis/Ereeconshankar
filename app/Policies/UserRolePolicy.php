<?php

namespace App\Policies;

use App\Models\UserRole;
use App\Models\User;

class UserRolePolicy
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
        return $this->canAny($user, ['user-roles.view', 'user_roles.view', 'userRole.view', 'user-role.view', 'view user roles']);
    }

    public function view(User $user, UserRole $record): bool
    {
        return $this->canAny($user, ['user-roles.view', 'user_roles.view', 'userRole.view', 'user-role.view', 'view user roles']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['user-roles.create', 'user_roles.create', 'userRole.create', 'user-role.create', 'create user roles']);
    }

    public function update(User $user, UserRole $record): bool
    {
        return $this->canAny($user, ['user-roles.update', 'user_roles.update', 'userRole.update', 'user-role.update', 'edit user roles', 'update user roles']);
    }

    public function delete(User $user, UserRole $record): bool
    {
        return $this->canAny($user, ['user-roles.delete', 'user_roles.delete', 'userRole.delete', 'user-role.delete', 'delete user roles']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['user-roles.delete', 'user_roles.delete', 'userRole.delete', 'user-role.delete', 'delete user roles']);
    }

    public function forceDelete(User $user, UserRole $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, UserRole $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, UserRole $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['user-roles.update', 'user_roles.update', 'userRole.update', 'user-role.update', 'edit user roles', 'update user roles']);
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