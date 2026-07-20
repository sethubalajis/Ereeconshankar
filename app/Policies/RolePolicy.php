<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;

class RolePolicy
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
        return $this->canAny($user, ['roles.view', 'roles.view', 'role.view', 'role.view', 'view roles']);
    }

    public function view(User $user, Role $record): bool
    {
        return $this->canAny($user, ['roles.view', 'roles.view', 'role.view', 'role.view', 'view roles']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['roles.create', 'roles.create', 'role.create', 'role.create', 'create roles']);
    }

    public function update(User $user, Role $record): bool
    {
        return $this->canAny($user, ['roles.update', 'roles.update', 'role.update', 'role.update', 'edit roles', 'update roles']);
    }

    public function delete(User $user, Role $record): bool
    {
        return $this->canAny($user, ['roles.delete', 'roles.delete', 'role.delete', 'role.delete', 'delete roles']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['roles.delete', 'roles.delete', 'role.delete', 'role.delete', 'delete roles']);
    }

    public function forceDelete(User $user, Role $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, Role $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, Role $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['roles.update', 'roles.update', 'role.update', 'role.update', 'edit roles', 'update roles']);
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