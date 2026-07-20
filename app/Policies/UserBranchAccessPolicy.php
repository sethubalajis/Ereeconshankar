<?php

namespace App\Policies;

use App\Models\UserBranchAccess;
use App\Models\User;

class UserBranchAccessPolicy
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
        return $this->canAny($user, ['user-branch-accesses.view', 'user_branch_accesses.view', 'userBranchAccess.view', 'user-branch-access.view', 'view user branch accesses']);
    }

    public function view(User $user, UserBranchAccess $record): bool
    {
        return $this->canAny($user, ['user-branch-accesses.view', 'user_branch_accesses.view', 'userBranchAccess.view', 'user-branch-access.view', 'view user branch accesses']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['user-branch-accesses.create', 'user_branch_accesses.create', 'userBranchAccess.create', 'user-branch-access.create', 'create user branch accesses']);
    }

    public function update(User $user, UserBranchAccess $record): bool
    {
        return $this->canAny($user, ['user-branch-accesses.update', 'user_branch_accesses.update', 'userBranchAccess.update', 'user-branch-access.update', 'edit user branch accesses', 'update user branch accesses']);
    }

    public function delete(User $user, UserBranchAccess $record): bool
    {
        return $this->canAny($user, ['user-branch-accesses.delete', 'user_branch_accesses.delete', 'userBranchAccess.delete', 'user-branch-access.delete', 'delete user branch accesses']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['user-branch-accesses.delete', 'user_branch_accesses.delete', 'userBranchAccess.delete', 'user-branch-access.delete', 'delete user branch accesses']);
    }

    public function forceDelete(User $user, UserBranchAccess $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, UserBranchAccess $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, UserBranchAccess $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['user-branch-accesses.update', 'user_branch_accesses.update', 'userBranchAccess.update', 'user-branch-access.update', 'edit user branch accesses', 'update user branch accesses']);
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