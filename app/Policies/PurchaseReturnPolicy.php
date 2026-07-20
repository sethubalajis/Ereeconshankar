<?php

namespace App\Policies;

use App\Models\PurchaseReturn;
use App\Models\User;

class PurchaseReturnPolicy
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
        return $this->canAny($user, ['purchase-returns.view', 'purchase_returns.view', 'purchaseReturn.view', 'purchase-return.view', 'view purchase returns']);
    }

    public function view(User $user, PurchaseReturn $record): bool
    {
        return $this->canAny($user, ['purchase-returns.view', 'purchase_returns.view', 'purchaseReturn.view', 'purchase-return.view', 'view purchase returns']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['purchase-returns.create', 'purchase_returns.create', 'purchaseReturn.create', 'purchase-return.create', 'create purchase returns']);
    }

    public function update(User $user, PurchaseReturn $record): bool
    {
        return $this->canAny($user, ['purchase-returns.update', 'purchase_returns.update', 'purchaseReturn.update', 'purchase-return.update', 'edit purchase returns', 'update purchase returns']);
    }

    public function delete(User $user, PurchaseReturn $record): bool
    {
        return $this->canAny($user, ['purchase-returns.delete', 'purchase_returns.delete', 'purchaseReturn.delete', 'purchase-return.delete', 'delete purchase returns']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['purchase-returns.delete', 'purchase_returns.delete', 'purchaseReturn.delete', 'purchase-return.delete', 'delete purchase returns']);
    }

    public function forceDelete(User $user, PurchaseReturn $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, PurchaseReturn $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, PurchaseReturn $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['purchase-returns.update', 'purchase_returns.update', 'purchaseReturn.update', 'purchase-return.update', 'edit purchase returns', 'update purchase returns']);
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