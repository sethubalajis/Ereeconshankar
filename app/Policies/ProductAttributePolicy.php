<?php

namespace App\Policies;

use App\Models\ProductAttribute;
use App\Models\User;

class ProductAttributePolicy
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
        return $this->canAny($user, ['product-attributes.view', 'product_attributes.view', 'productAttribute.view', 'product-attribute.view', 'view product attributes']);
    }

    public function view(User $user, ProductAttribute $productAttribute): bool
    {
        return $this->canAny($user, ['product-attributes.view', 'product_attributes.view', 'productAttribute.view', 'product-attribute.view', 'view product attributes']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['product-attributes.create', 'product_attributes.create', 'productAttribute.create', 'product-attribute.create', 'create product attributes']);
    }

    public function update(User $user, ProductAttribute $productAttribute): bool
    {
        return $this->canAny($user, ['product-attributes.update', 'product_attributes.update', 'productAttribute.update', 'product-attribute.update', 'edit product attributes', 'update product attributes']);
    }

    public function delete(User $user, ProductAttribute $productAttribute): bool
    {
        return $this->canAny($user, ['product-attributes.delete', 'product_attributes.delete', 'productAttribute.delete', 'product-attribute.delete', 'delete product attributes']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['product-attributes.delete', 'product_attributes.delete', 'productAttribute.delete', 'product-attribute.delete', 'delete product attributes']);
    }

    public function forceDelete(User $user, ProductAttribute $productAttribute): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, ProductAttribute $productAttribute): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, ProductAttribute $productAttribute): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['product-attributes.update', 'product_attributes.update', 'productAttribute.update', 'product-attribute.update', 'edit product attributes', 'update product attributes']);
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
