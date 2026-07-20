<?php

namespace App\Policies;

use App\Models\StockTransfer;
use App\Models\User;

class StockTransferPolicy
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
        return $this->canAny($user, ['stock-transfers.view', 'stock_transfers.view', 'stockTransfer.view', 'stock-transfer.view', 'view stock transfers']);
    }

    public function view(User $user, StockTransfer $record): bool
    {
        return $this->canAny($user, ['stock-transfers.view', 'stock_transfers.view', 'stockTransfer.view', 'stock-transfer.view', 'view stock transfers']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['stock-transfers.create', 'stock_transfers.create', 'stockTransfer.create', 'stock-transfer.create', 'create stock transfers']);
    }

    public function update(User $user, StockTransfer $record): bool
    {
        return $this->canAny($user, ['stock-transfers.update', 'stock_transfers.update', 'stockTransfer.update', 'stock-transfer.update', 'edit stock transfers', 'update stock transfers']);
    }

    public function delete(User $user, StockTransfer $record): bool
    {
        return $this->canAny($user, ['stock-transfers.delete', 'stock_transfers.delete', 'stockTransfer.delete', 'stock-transfer.delete', 'delete stock transfers']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['stock-transfers.delete', 'stock_transfers.delete', 'stockTransfer.delete', 'stock-transfer.delete', 'delete stock transfers']);
    }

    public function forceDelete(User $user, StockTransfer $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, StockTransfer $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, StockTransfer $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['stock-transfers.update', 'stock_transfers.update', 'stockTransfer.update', 'stock-transfer.update', 'edit stock transfers', 'update stock transfers']);
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