<?php

namespace App\Policies;

use App\Models\WarehouseZone;
use App\Models\User;

class WarehouseZonePolicy
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
        return $this->canAny($user, ['warehouse-zones.view', 'warehouse_zones.view', 'warehouseZone.view', 'warehouse-zone.view', 'view warehouse zones']);
    }

    public function view(User $user, WarehouseZone $record): bool
    {
        return $this->canAny($user, ['warehouse-zones.view', 'warehouse_zones.view', 'warehouseZone.view', 'warehouse-zone.view', 'view warehouse zones']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['warehouse-zones.create', 'warehouse_zones.create', 'warehouseZone.create', 'warehouse-zone.create', 'create warehouse zones']);
    }

    public function update(User $user, WarehouseZone $record): bool
    {
        return $this->canAny($user, ['warehouse-zones.update', 'warehouse_zones.update', 'warehouseZone.update', 'warehouse-zone.update', 'edit warehouse zones', 'update warehouse zones']);
    }

    public function delete(User $user, WarehouseZone $record): bool
    {
        return $this->canAny($user, ['warehouse-zones.delete', 'warehouse_zones.delete', 'warehouseZone.delete', 'warehouse-zone.delete', 'delete warehouse zones']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['warehouse-zones.delete', 'warehouse_zones.delete', 'warehouseZone.delete', 'warehouse-zone.delete', 'delete warehouse zones']);
    }

    public function forceDelete(User $user, WarehouseZone $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, WarehouseZone $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, WarehouseZone $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['warehouse-zones.update', 'warehouse_zones.update', 'warehouseZone.update', 'warehouse-zone.update', 'edit warehouse zones', 'update warehouse zones']);
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