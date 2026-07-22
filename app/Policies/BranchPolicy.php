<?php

namespace App\Policies;

use App\Models\Branch;
use App\Models\User;

class BranchPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        if ($user->hasAnyRole(['Branch Manager', 'Branch Admin', 'branch manager', 'branch admin'])
            && method_exists($user, 'branches')
            && $user->branches()->exists()) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $this->canAny($user, ['branches.view', 'branches.view', 'branch.view', 'branch.view', 'view branches']);
    }

    public function view(User $user, Branch $record): bool
    {
        return $this->canAny($user, ['branches.view', 'branches.view', 'branch.view', 'branch.view', 'view branches']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['branches.create', 'branches.create', 'branch.create', 'branch.create', 'create branches']);
    }

    public function update(User $user, Branch $record): bool
    {
        return $this->canAny($user, ['branches.update', 'branches.update', 'branch.update', 'branch.update', 'edit branches', 'update branches']);
    }

    public function delete(User $user, Branch $record): bool
    {
        return $this->canAny($user, ['branches.delete', 'branches.delete', 'branch.delete', 'branch.delete', 'delete branches']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['branches.delete', 'branches.delete', 'branch.delete', 'branch.delete', 'delete branches']);
    }

    public function forceDelete(User $user, Branch $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, Branch $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, Branch $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['branches.update', 'branches.update', 'branch.update', 'branch.update', 'edit branches', 'update branches']);
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