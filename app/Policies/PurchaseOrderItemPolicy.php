<?php

namespace App\Policies;

use App\Models\PurchaseOrderItem;
use App\Models\User;

class PurchaseOrderItemPolicy
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
        return $this->canAny($user, ['purchase-order-items.view', 'purchase_order_items.view', 'purchaseOrderItem.view', 'purchase-order-item.view', 'view purchase order items']);
    }

    public function view(User $user, PurchaseOrderItem $record): bool
    {
        return $this->canAny($user, ['purchase-order-items.view', 'purchase_order_items.view', 'purchaseOrderItem.view', 'purchase-order-item.view', 'view purchase order items']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['purchase-order-items.create', 'purchase_order_items.create', 'purchaseOrderItem.create', 'purchase-order-item.create', 'create purchase order items']);
    }

    public function update(User $user, PurchaseOrderItem $record): bool
    {
        return $this->canAny($user, ['purchase-order-items.update', 'purchase_order_items.update', 'purchaseOrderItem.update', 'purchase-order-item.update', 'edit purchase order items', 'update purchase order items']);
    }

    public function delete(User $user, PurchaseOrderItem $record): bool
    {
        return $this->canAny($user, ['purchase-order-items.delete', 'purchase_order_items.delete', 'purchaseOrderItem.delete', 'purchase-order-item.delete', 'delete purchase order items']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['purchase-order-items.delete', 'purchase_order_items.delete', 'purchaseOrderItem.delete', 'purchase-order-item.delete', 'delete purchase order items']);
    }

    public function forceDelete(User $user, PurchaseOrderItem $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, PurchaseOrderItem $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, PurchaseOrderItem $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['purchase-order-items.update', 'purchase_order_items.update', 'purchaseOrderItem.update', 'purchase-order-item.update', 'edit purchase order items', 'update purchase order items']);
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