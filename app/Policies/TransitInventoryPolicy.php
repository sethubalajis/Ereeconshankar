<?php

namespace App\Policies;

use App\Models\TransitInventory;
use App\Models\User;

class TransitInventoryPolicy
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
        return $this->canAny($user, ['transit-inventories.view', 'transit_inventories.view', 'transitInventory.view', 'transit-inventory.view', 'view transit inventories']);
    }

    public function view(User $user, TransitInventory $record): bool
    {
        return $this->canAny($user, ['transit-inventories.view', 'transit_inventories.view', 'transitInventory.view', 'transit-inventory.view', 'view transit inventories']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['transit-inventories.create', 'transit_inventories.create', 'transitInventory.create', 'transit-inventory.create', 'create transit inventories']);
    }

    public function update(User $user, TransitInventory $record): bool
    {
        return $this->canAny($user, ['transit-inventories.update', 'transit_inventories.update', 'transitInventory.update', 'transit-inventory.update', 'edit transit inventories', 'update transit inventories']);
    }

    public function delete(User $user, TransitInventory $record): bool
    {
        return $this->canAny($user, ['transit-inventories.delete', 'transit_inventories.delete', 'transitInventory.delete', 'transit-inventory.delete', 'delete transit inventories']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['transit-inventories.delete', 'transit_inventories.delete', 'transitInventory.delete', 'transit-inventory.delete', 'delete transit inventories']);
    }

    public function forceDelete(User $user, TransitInventory $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, TransitInventory $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, TransitInventory $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['transit-inventories.update', 'transit_inventories.update', 'transitInventory.update', 'transit-inventory.update', 'edit transit inventories', 'update transit inventories']);
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