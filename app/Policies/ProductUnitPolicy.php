<?php

namespace App\Policies;

use App\Models\ProductUnit;
use App\Models\User;

class ProductUnitPolicy
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
        return $this->canAny($user, ['product-units.view', 'product_units.view', 'productUnit.view', 'product-unit.view', 'view product units']);
    }

    public function view(User $user, ProductUnit $productUnit): bool
    {
        return $this->canAny($user, ['product-units.view', 'product_units.view', 'productUnit.view', 'product-unit.view', 'view product units']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['product-units.create', 'product_units.create', 'productUnit.create', 'product-unit.create', 'create product units']);
    }

    public function update(User $user, ProductUnit $productUnit): bool
    {
        return $this->canAny($user, ['product-units.update', 'product_units.update', 'productUnit.update', 'product-unit.update', 'edit product units', 'update product units']);
    }

    public function delete(User $user, ProductUnit $productUnit): bool
    {
        return $this->canAny($user, ['product-units.delete', 'product_units.delete', 'productUnit.delete', 'product-unit.delete', 'delete product units']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['product-units.delete', 'product_units.delete', 'productUnit.delete', 'product-unit.delete', 'delete product units']);
    }

    public function forceDelete(User $user, ProductUnit $productUnit): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, ProductUnit $productUnit): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, ProductUnit $productUnit): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['product-units.update', 'product_units.update', 'productUnit.update', 'product-unit.update', 'edit product units', 'update product units']);
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
