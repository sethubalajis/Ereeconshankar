<?php

namespace App\Policies;

use App\Models\PriceList;
use App\Models\User;

class PriceListPolicy
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
        return $this->canAny($user, ['price-lists.view', 'price_lists.view', 'priceList.view', 'price-list.view', 'view price lists']);
    }

    public function view(User $user, PriceList $record): bool
    {
        return $this->canAny($user, ['price-lists.view', 'price_lists.view', 'priceList.view', 'price-list.view', 'view price lists']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['price-lists.create', 'price_lists.create', 'priceList.create', 'price-list.create', 'create price lists']);
    }

    public function update(User $user, PriceList $record): bool
    {
        return $this->canAny($user, ['price-lists.update', 'price_lists.update', 'priceList.update', 'price-list.update', 'edit price lists', 'update price lists']);
    }

    public function delete(User $user, PriceList $record): bool
    {
        return $this->canAny($user, ['price-lists.delete', 'price_lists.delete', 'priceList.delete', 'price-list.delete', 'delete price lists']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['price-lists.delete', 'price_lists.delete', 'priceList.delete', 'price-list.delete', 'delete price lists']);
    }

    public function forceDelete(User $user, PriceList $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, PriceList $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, PriceList $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['price-lists.update', 'price_lists.update', 'priceList.update', 'price-list.update', 'edit price lists', 'update price lists']);
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