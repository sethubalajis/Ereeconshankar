<?php

namespace App\Policies;

use App\Models\InventoryReconciliation;
use App\Models\User;

class InventoryReconciliationPolicy
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
        return $this->canAny($user, ['inventory-reconciliations.view', 'inventory_reconciliations.view', 'inventoryReconciliation.view', 'inventory-reconciliation.view', 'view inventory reconciliations']);
    }

    public function view(User $user, InventoryReconciliation $record): bool
    {
        return $this->canAny($user, ['inventory-reconciliations.view', 'inventory_reconciliations.view', 'inventoryReconciliation.view', 'inventory-reconciliation.view', 'view inventory reconciliations']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['inventory-reconciliations.create', 'inventory_reconciliations.create', 'inventoryReconciliation.create', 'inventory-reconciliation.create', 'create inventory reconciliations']);
    }

    public function update(User $user, InventoryReconciliation $record): bool
    {
        return $this->canAny($user, ['inventory-reconciliations.update', 'inventory_reconciliations.update', 'inventoryReconciliation.update', 'inventory-reconciliation.update', 'edit inventory reconciliations', 'update inventory reconciliations']);
    }

    public function delete(User $user, InventoryReconciliation $record): bool
    {
        return $this->canAny($user, ['inventory-reconciliations.delete', 'inventory_reconciliations.delete', 'inventoryReconciliation.delete', 'inventory-reconciliation.delete', 'delete inventory reconciliations']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['inventory-reconciliations.delete', 'inventory_reconciliations.delete', 'inventoryReconciliation.delete', 'inventory-reconciliation.delete', 'delete inventory reconciliations']);
    }

    public function forceDelete(User $user, InventoryReconciliation $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, InventoryReconciliation $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, InventoryReconciliation $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['inventory-reconciliations.update', 'inventory_reconciliations.update', 'inventoryReconciliation.update', 'inventory-reconciliation.update', 'edit inventory reconciliations', 'update inventory reconciliations']);
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