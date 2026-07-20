<?php

namespace App\Policies;

use App\Models\BranchInventory;
use App\Models\User;

class BranchInventoryPolicy
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
        return $this->canAny($user, ['branch-inventories.view', 'branch_inventories.view', 'branchInventory.view', 'branch-inventory.view', 'view branch inventories']);
    }

    public function view(User $user, BranchInventory $record): bool
    {
        return $this->canAny($user, ['branch-inventories.view', 'branch_inventories.view', 'branchInventory.view', 'branch-inventory.view', 'view branch inventories']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['branch-inventories.create', 'branch_inventories.create', 'branchInventory.create', 'branch-inventory.create', 'create branch inventories']);
    }

    public function update(User $user, BranchInventory $record): bool
    {
        return $this->canAny($user, ['branch-inventories.update', 'branch_inventories.update', 'branchInventory.update', 'branch-inventory.update', 'edit branch inventories', 'update branch inventories']);
    }

    public function delete(User $user, BranchInventory $record): bool
    {
        return $this->canAny($user, ['branch-inventories.delete', 'branch_inventories.delete', 'branchInventory.delete', 'branch-inventory.delete', 'delete branch inventories']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['branch-inventories.delete', 'branch_inventories.delete', 'branchInventory.delete', 'branch-inventory.delete', 'delete branch inventories']);
    }

    public function forceDelete(User $user, BranchInventory $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, BranchInventory $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, BranchInventory $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['branch-inventories.update', 'branch_inventories.update', 'branchInventory.update', 'branch-inventory.update', 'edit branch inventories', 'update branch inventories']);
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