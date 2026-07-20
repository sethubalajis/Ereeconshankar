<?php

namespace App\Policies;

use App\Models\PosSaleItem;
use App\Models\User;

class PosSaleItemPolicy
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
        return $this->canAny($user, ['pos-sale-items.view', 'pos_sale_items.view', 'posSaleItem.view', 'pos-sale-item.view', 'view pos sale items']);
    }

    public function view(User $user, PosSaleItem $record): bool
    {
        return $this->canAny($user, ['pos-sale-items.view', 'pos_sale_items.view', 'posSaleItem.view', 'pos-sale-item.view', 'view pos sale items']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['pos-sale-items.create', 'pos_sale_items.create', 'posSaleItem.create', 'pos-sale-item.create', 'create pos sale items']);
    }

    public function update(User $user, PosSaleItem $record): bool
    {
        return $this->canAny($user, ['pos-sale-items.update', 'pos_sale_items.update', 'posSaleItem.update', 'pos-sale-item.update', 'edit pos sale items', 'update pos sale items']);
    }

    public function delete(User $user, PosSaleItem $record): bool
    {
        return $this->canAny($user, ['pos-sale-items.delete', 'pos_sale_items.delete', 'posSaleItem.delete', 'pos-sale-item.delete', 'delete pos sale items']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['pos-sale-items.delete', 'pos_sale_items.delete', 'posSaleItem.delete', 'pos-sale-item.delete', 'delete pos sale items']);
    }

    public function forceDelete(User $user, PosSaleItem $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, PosSaleItem $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, PosSaleItem $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['pos-sale-items.update', 'pos_sale_items.update', 'posSaleItem.update', 'pos-sale-item.update', 'edit pos sale items', 'update pos sale items']);
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