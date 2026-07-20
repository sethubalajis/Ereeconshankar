<?php

namespace App\Policies;

use App\Models\BranchTarget;
use App\Models\User;

class BranchTargetPolicy
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
        return $this->canAny($user, ['branch-targets.view', 'branch_targets.view', 'branchTarget.view', 'branch-target.view', 'view branch targets']);
    }

    public function view(User $user, BranchTarget $record): bool
    {
        return $this->canAny($user, ['branch-targets.view', 'branch_targets.view', 'branchTarget.view', 'branch-target.view', 'view branch targets']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['branch-targets.create', 'branch_targets.create', 'branchTarget.create', 'branch-target.create', 'create branch targets']);
    }

    public function update(User $user, BranchTarget $record): bool
    {
        return $this->canAny($user, ['branch-targets.update', 'branch_targets.update', 'branchTarget.update', 'branch-target.update', 'edit branch targets', 'update branch targets']);
    }

    public function delete(User $user, BranchTarget $record): bool
    {
        return $this->canAny($user, ['branch-targets.delete', 'branch_targets.delete', 'branchTarget.delete', 'branch-target.delete', 'delete branch targets']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['branch-targets.delete', 'branch_targets.delete', 'branchTarget.delete', 'branch-target.delete', 'delete branch targets']);
    }

    public function forceDelete(User $user, BranchTarget $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, BranchTarget $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, BranchTarget $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['branch-targets.update', 'branch_targets.update', 'branchTarget.update', 'branch-target.update', 'edit branch targets', 'update branch targets']);
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