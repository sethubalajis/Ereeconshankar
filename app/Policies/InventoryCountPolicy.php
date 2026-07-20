<?php

namespace App\Policies;

use App\Models\InventoryCount;
use App\Models\User;

class InventoryCountPolicy
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
        return $this->canAny($user, ['inventory-counts.view', 'inventory_counts.view', 'inventoryCount.view', 'inventory-count.view', 'view inventory counts']);
    }

    public function view(User $user, InventoryCount $record): bool
    {
        return $this->canAny($user, ['inventory-counts.view', 'inventory_counts.view', 'inventoryCount.view', 'inventory-count.view', 'view inventory counts']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['inventory-counts.create', 'inventory_counts.create', 'inventoryCount.create', 'inventory-count.create', 'create inventory counts']);
    }

    public function update(User $user, InventoryCount $record): bool
    {
        return $this->canAny($user, ['inventory-counts.update', 'inventory_counts.update', 'inventoryCount.update', 'inventory-count.update', 'edit inventory counts', 'update inventory counts']);
    }

    public function delete(User $user, InventoryCount $record): bool
    {
        return $this->canAny($user, ['inventory-counts.delete', 'inventory_counts.delete', 'inventoryCount.delete', 'inventory-count.delete', 'delete inventory counts']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['inventory-counts.delete', 'inventory_counts.delete', 'inventoryCount.delete', 'inventory-count.delete', 'delete inventory counts']);
    }

    public function forceDelete(User $user, InventoryCount $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, InventoryCount $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, InventoryCount $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['inventory-counts.update', 'inventory_counts.update', 'inventoryCount.update', 'inventory-count.update', 'edit inventory counts', 'update inventory counts']);
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