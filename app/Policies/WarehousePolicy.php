<?php

namespace App\Policies;

use App\Models\Warehouse;
use App\Models\User;

class WarehousePolicy
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
        return $this->canAny($user, ['warehouses.view', 'warehouses.view', 'warehouse.view', 'warehouse.view', 'view warehouses']);
    }

    public function view(User $user, Warehouse $record): bool
    {
        return $this->canAny($user, ['warehouses.view', 'warehouses.view', 'warehouse.view', 'warehouse.view', 'view warehouses']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['warehouses.create', 'warehouses.create', 'warehouse.create', 'warehouse.create', 'create warehouses']);
    }

    public function update(User $user, Warehouse $record): bool
    {
        return $this->canAny($user, ['warehouses.update', 'warehouses.update', 'warehouse.update', 'warehouse.update', 'edit warehouses', 'update warehouses']);
    }

    public function delete(User $user, Warehouse $record): bool
    {
        return $this->canAny($user, ['warehouses.delete', 'warehouses.delete', 'warehouse.delete', 'warehouse.delete', 'delete warehouses']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['warehouses.delete', 'warehouses.delete', 'warehouse.delete', 'warehouse.delete', 'delete warehouses']);
    }

    public function forceDelete(User $user, Warehouse $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, Warehouse $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, Warehouse $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['warehouses.update', 'warehouses.update', 'warehouse.update', 'warehouse.update', 'edit warehouses', 'update warehouses']);
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