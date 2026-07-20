<?php

namespace App\Policies;

use App\Models\VendorPriceList;
use App\Models\User;

class VendorPriceListPolicy
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
        return $this->canAny($user, ['vendor-price-lists.view', 'vendor_price_lists.view', 'vendorPriceList.view', 'vendor-price-list.view', 'view vendor price lists']);
    }

    public function view(User $user, VendorPriceList $record): bool
    {
        return $this->canAny($user, ['vendor-price-lists.view', 'vendor_price_lists.view', 'vendorPriceList.view', 'vendor-price-list.view', 'view vendor price lists']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['vendor-price-lists.create', 'vendor_price_lists.create', 'vendorPriceList.create', 'vendor-price-list.create', 'create vendor price lists']);
    }

    public function update(User $user, VendorPriceList $record): bool
    {
        return $this->canAny($user, ['vendor-price-lists.update', 'vendor_price_lists.update', 'vendorPriceList.update', 'vendor-price-list.update', 'edit vendor price lists', 'update vendor price lists']);
    }

    public function delete(User $user, VendorPriceList $record): bool
    {
        return $this->canAny($user, ['vendor-price-lists.delete', 'vendor_price_lists.delete', 'vendorPriceList.delete', 'vendor-price-list.delete', 'delete vendor price lists']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['vendor-price-lists.delete', 'vendor_price_lists.delete', 'vendorPriceList.delete', 'vendor-price-list.delete', 'delete vendor price lists']);
    }

    public function forceDelete(User $user, VendorPriceList $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, VendorPriceList $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, VendorPriceList $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['vendor-price-lists.update', 'vendor_price_lists.update', 'vendorPriceList.update', 'vendor-price-list.update', 'edit vendor price lists', 'update vendor price lists']);
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