<?php

namespace App\Policies;

use App\Models\TransferItem;
use App\Models\User;

class TransferItemPolicy
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
        return $this->canAny($user, ['transfer-items.view', 'transfer_items.view', 'transferItem.view', 'transfer-item.view', 'view transfer items']);
    }

    public function view(User $user, TransferItem $record): bool
    {
        return $this->canAny($user, ['transfer-items.view', 'transfer_items.view', 'transferItem.view', 'transfer-item.view', 'view transfer items']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['transfer-items.create', 'transfer_items.create', 'transferItem.create', 'transfer-item.create', 'create transfer items']);
    }

    public function update(User $user, TransferItem $record): bool
    {
        return $this->canAny($user, ['transfer-items.update', 'transfer_items.update', 'transferItem.update', 'transfer-item.update', 'edit transfer items', 'update transfer items']);
    }

    public function delete(User $user, TransferItem $record): bool
    {
        return $this->canAny($user, ['transfer-items.delete', 'transfer_items.delete', 'transferItem.delete', 'transfer-item.delete', 'delete transfer items']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['transfer-items.delete', 'transfer_items.delete', 'transferItem.delete', 'transfer-item.delete', 'delete transfer items']);
    }

    public function forceDelete(User $user, TransferItem $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, TransferItem $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, TransferItem $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['transfer-items.update', 'transfer_items.update', 'transferItem.update', 'transfer-item.update', 'edit transfer items', 'update transfer items']);
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