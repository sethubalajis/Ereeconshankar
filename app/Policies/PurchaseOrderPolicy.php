<?php

namespace App\Policies;

use App\Models\PurchaseOrder;
use App\Models\User;

class PurchaseOrderPolicy
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
        return $this->canAny($user, ['purchase-orders.view', 'purchase_orders.view', 'purchaseOrder.view', 'purchase-order.view', 'view purchase orders']);
    }

    public function view(User $user, PurchaseOrder $record): bool
    {
        return $this->canAny($user, ['purchase-orders.view', 'purchase_orders.view', 'purchaseOrder.view', 'purchase-order.view', 'view purchase orders']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['purchase-orders.create', 'purchase_orders.create', 'purchaseOrder.create', 'purchase-order.create', 'create purchase orders']);
    }

    public function update(User $user, PurchaseOrder $record): bool
    {
        return $this->canAny($user, ['purchase-orders.update', 'purchase_orders.update', 'purchaseOrder.update', 'purchase-order.update', 'edit purchase orders', 'update purchase orders']);
    }

    public function delete(User $user, PurchaseOrder $record): bool
    {
        return $this->canAny($user, ['purchase-orders.delete', 'purchase_orders.delete', 'purchaseOrder.delete', 'purchase-order.delete', 'delete purchase orders']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['purchase-orders.delete', 'purchase_orders.delete', 'purchaseOrder.delete', 'purchase-order.delete', 'delete purchase orders']);
    }

    public function forceDelete(User $user, PurchaseOrder $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, PurchaseOrder $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, PurchaseOrder $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['purchase-orders.update', 'purchase_orders.update', 'purchaseOrder.update', 'purchase-order.update', 'edit purchase orders', 'update purchase orders']);
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