<?php

namespace App\Policies;

use App\Models\BranchUser;
use App\Models\User;

class BranchUserPolicy
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
        return $this->canAny($user, ['branch-users.view', 'branch_users.view', 'branchUser.view', 'branch-user.view', 'view branch users']);
    }

    public function view(User $user, BranchUser $record): bool
    {
        return $this->canAny($user, ['branch-users.view', 'branch_users.view', 'branchUser.view', 'branch-user.view', 'view branch users']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['branch-users.create', 'branch_users.create', 'branchUser.create', 'branch-user.create', 'create branch users']);
    }

    public function update(User $user, BranchUser $record): bool
    {
        return $this->canAny($user, ['branch-users.update', 'branch_users.update', 'branchUser.update', 'branch-user.update', 'edit branch users', 'update branch users']);
    }

    public function delete(User $user, BranchUser $record): bool
    {
        return $this->canAny($user, ['branch-users.delete', 'branch_users.delete', 'branchUser.delete', 'branch-user.delete', 'delete branch users']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['branch-users.delete', 'branch_users.delete', 'branchUser.delete', 'branch-user.delete', 'delete branch users']);
    }

    public function forceDelete(User $user, BranchUser $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, BranchUser $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, BranchUser $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['branch-users.update', 'branch_users.update', 'branchUser.update', 'branch-user.update', 'edit branch users', 'update branch users']);
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