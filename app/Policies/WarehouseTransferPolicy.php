<?php

namespace App\Policies;

use App\Models\WarehouseTransfer;
use App\Models\User;

class WarehouseTransferPolicy
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
        return $this->canAny($user, ['warehouse-transfers.view', 'warehouse_transfers.view', 'warehouseTransfer.view', 'warehouse-transfer.view', 'view warehouse transfers']);
    }

    public function view(User $user, WarehouseTransfer $record): bool
    {
        return $this->canAny($user, ['warehouse-transfers.view', 'warehouse_transfers.view', 'warehouseTransfer.view', 'warehouse-transfer.view', 'view warehouse transfers']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['warehouse-transfers.create', 'warehouse_transfers.create', 'warehouseTransfer.create', 'warehouse-transfer.create', 'create warehouse transfers']);
    }

    public function update(User $user, WarehouseTransfer $record): bool
    {
        return $this->canAny($user, ['warehouse-transfers.update', 'warehouse_transfers.update', 'warehouseTransfer.update', 'warehouse-transfer.update', 'edit warehouse transfers', 'update warehouse transfers']);
    }

    public function delete(User $user, WarehouseTransfer $record): bool
    {
        return $this->canAny($user, ['warehouse-transfers.delete', 'warehouse_transfers.delete', 'warehouseTransfer.delete', 'warehouse-transfer.delete', 'delete warehouse transfers']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['warehouse-transfers.delete', 'warehouse_transfers.delete', 'warehouseTransfer.delete', 'warehouse-transfer.delete', 'delete warehouse transfers']);
    }

    public function forceDelete(User $user, WarehouseTransfer $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, WarehouseTransfer $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, WarehouseTransfer $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['warehouse-transfers.update', 'warehouse_transfers.update', 'warehouseTransfer.update', 'warehouse-transfer.update', 'edit warehouse transfers', 'update warehouse transfers']);
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