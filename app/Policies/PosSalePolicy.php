<?php

namespace App\Policies;

use App\Models\PosSale;
use App\Models\User;

class PosSalePolicy
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
        return $this->canAny($user, ['pos-sales.view', 'pos_sales.view', 'posSale.view', 'pos-sale.view', 'view pos sales']);
    }

    public function view(User $user, PosSale $record): bool
    {
        return $this->canAny($user, ['pos-sales.view', 'pos_sales.view', 'posSale.view', 'pos-sale.view', 'view pos sales']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['pos-sales.create', 'pos_sales.create', 'posSale.create', 'pos-sale.create', 'create pos sales']);
    }

    public function update(User $user, PosSale $record): bool
    {
        return $this->canAny($user, ['pos-sales.update', 'pos_sales.update', 'posSale.update', 'pos-sale.update', 'edit pos sales', 'update pos sales']);
    }

    public function delete(User $user, PosSale $record): bool
    {
        return $this->canAny($user, ['pos-sales.delete', 'pos_sales.delete', 'posSale.delete', 'pos-sale.delete', 'delete pos sales']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['pos-sales.delete', 'pos_sales.delete', 'posSale.delete', 'pos-sale.delete', 'delete pos sales']);
    }

    public function forceDelete(User $user, PosSale $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, PosSale $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, PosSale $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['pos-sales.update', 'pos_sales.update', 'posSale.update', 'pos-sale.update', 'edit pos sales', 'update pos sales']);
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