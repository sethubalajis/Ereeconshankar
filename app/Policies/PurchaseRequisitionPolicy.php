<?php

namespace App\Policies;

use App\Models\PurchaseRequisition;
use App\Models\User;

class PurchaseRequisitionPolicy
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
        return $this->canAny($user, ['purchase-requisitions.view', 'purchase_requisitions.view', 'purchaseRequisition.view', 'purchase-requisition.view', 'view purchase requisitions']);
    }

    public function view(User $user, PurchaseRequisition $record): bool
    {
        return $this->canAny($user, ['purchase-requisitions.view', 'purchase_requisitions.view', 'purchaseRequisition.view', 'purchase-requisition.view', 'view purchase requisitions']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['purchase-requisitions.create', 'purchase_requisitions.create', 'purchaseRequisition.create', 'purchase-requisition.create', 'create purchase requisitions']);
    }

    public function update(User $user, PurchaseRequisition $record): bool
    {
        return $this->canAny($user, ['purchase-requisitions.update', 'purchase_requisitions.update', 'purchaseRequisition.update', 'purchase-requisition.update', 'edit purchase requisitions', 'update purchase requisitions']);
    }

    public function delete(User $user, PurchaseRequisition $record): bool
    {
        return $this->canAny($user, ['purchase-requisitions.delete', 'purchase_requisitions.delete', 'purchaseRequisition.delete', 'purchase-requisition.delete', 'delete purchase requisitions']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['purchase-requisitions.delete', 'purchase_requisitions.delete', 'purchaseRequisition.delete', 'purchase-requisition.delete', 'delete purchase requisitions']);
    }

    public function forceDelete(User $user, PurchaseRequisition $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, PurchaseRequisition $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, PurchaseRequisition $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['purchase-requisitions.update', 'purchase_requisitions.update', 'purchaseRequisition.update', 'purchase-requisition.update', 'edit purchase requisitions', 'update purchase requisitions']);
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