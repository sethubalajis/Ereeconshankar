<?php

namespace App\Policies;

use App\Models\TransferReceipt;
use App\Models\User;

class TransferReceiptPolicy
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
        return $this->canAny($user, ['transfer-receipts.view', 'transfer_receipts.view', 'transferReceipt.view', 'transfer-receipt.view', 'view transfer receipts']);
    }

    public function view(User $user, TransferReceipt $record): bool
    {
        return $this->canAny($user, ['transfer-receipts.view', 'transfer_receipts.view', 'transferReceipt.view', 'transfer-receipt.view', 'view transfer receipts']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['transfer-receipts.create', 'transfer_receipts.create', 'transferReceipt.create', 'transfer-receipt.create', 'create transfer receipts']);
    }

    public function update(User $user, TransferReceipt $record): bool
    {
        return $this->canAny($user, ['transfer-receipts.update', 'transfer_receipts.update', 'transferReceipt.update', 'transfer-receipt.update', 'edit transfer receipts', 'update transfer receipts']);
    }

    public function delete(User $user, TransferReceipt $record): bool
    {
        return $this->canAny($user, ['transfer-receipts.delete', 'transfer_receipts.delete', 'transferReceipt.delete', 'transfer-receipt.delete', 'delete transfer receipts']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['transfer-receipts.delete', 'transfer_receipts.delete', 'transferReceipt.delete', 'transfer-receipt.delete', 'delete transfer receipts']);
    }

    public function forceDelete(User $user, TransferReceipt $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, TransferReceipt $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, TransferReceipt $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['transfer-receipts.update', 'transfer_receipts.update', 'transferReceipt.update', 'transfer-receipt.update', 'edit transfer receipts', 'update transfer receipts']);
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