<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function before(User $authUser, string $ability): bool|null
    {
        if ($authUser->hasRole('Super Admin')) {
            return true;
        }

        return null;
    }

    public function viewAny(User $authUser): bool
    {
        return $this->canAny($authUser, ['users.view', 'users.view', 'user.view', 'user.view', 'view users']);
    }

    public function view(User $authUser, User $record): bool
    {
        return $this->canAny($authUser, ['users.view', 'users.view', 'user.view', 'user.view', 'view users']);
    }

    public function create(User $authUser): bool
    {
        return $this->canAny($authUser, ['users.create', 'users.create', 'user.create', 'user.create', 'create users']);
    }

    public function update(User $authUser, User $record): bool
    {
        return $this->canAny($authUser, ['users.update', 'users.update', 'user.update', 'user.update', 'edit users', 'update users']);
    }

    public function delete(User $authUser, User $record): bool
    {
        return $this->canAny($authUser, ['users.delete', 'users.delete', 'user.delete', 'user.delete', 'delete users']);
    }

    public function deleteAny(User $authUser): bool
    {
        return $this->canAny($authUser, ['users.delete', 'users.delete', 'user.delete', 'user.delete', 'delete users']);
    }

    public function forceDelete(User $authUser, User $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $authUser): bool
    {
        return false;
    }

    public function restore(User $authUser, User $record): bool
    {
        return false;
    }

    public function restoreAny(User $authUser): bool
    {
        return false;
    }

    public function replicate(User $authUser, User $record): bool
    {
        return false;
    }

    public function reorder(User $authUser): bool
    {
        return $this->canAny($authUser, ['users.update', 'users.update', 'user.update', 'user.update', 'edit users', 'update users']);
    }

    private function canAny(User $authUser, array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($authUser->can($permission)) {
                return true;
            }
        }

        return false;
    }
}