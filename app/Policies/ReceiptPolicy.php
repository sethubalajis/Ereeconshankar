<?php

namespace App\Policies;

use App\Models\Receipt;
use App\Models\User;

class ReceiptPolicy
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
        return $this->canAny($user, ['receipts.view', 'receipts.view', 'receipt.view', 'receipt.view', 'view receipts']);
    }

    public function view(User $user, Receipt $record): bool
    {
        return $this->canAny($user, ['receipts.view', 'receipts.view', 'receipt.view', 'receipt.view', 'view receipts']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['receipts.create', 'receipts.create', 'receipt.create', 'receipt.create', 'create receipts']);
    }

    public function update(User $user, Receipt $record): bool
    {
        return $this->canAny($user, ['receipts.update', 'receipts.update', 'receipt.update', 'receipt.update', 'edit receipts', 'update receipts']);
    }

    public function delete(User $user, Receipt $record): bool
    {
        return $this->canAny($user, ['receipts.delete', 'receipts.delete', 'receipt.delete', 'receipt.delete', 'delete receipts']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['receipts.delete', 'receipts.delete', 'receipt.delete', 'receipt.delete', 'delete receipts']);
    }

    public function forceDelete(User $user, Receipt $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, Receipt $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, Receipt $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['receipts.update', 'receipts.update', 'receipt.update', 'receipt.update', 'edit receipts', 'update receipts']);
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