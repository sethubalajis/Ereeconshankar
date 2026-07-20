<?php

namespace App\Policies;

use App\Models\ProductVariant;
use App\Models\User;

class ProductVariantPolicy
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
        return $this->canAny($user, ['product-variants.view', 'product_variants.view', 'productVariant.view', 'product-variant.view', 'view product variants']);
    }

    public function view(User $user, ProductVariant $productVariant): bool
    {
        return $this->canAny($user, ['product-variants.view', 'product_variants.view', 'productVariant.view', 'product-variant.view', 'view product variants']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['product-variants.create', 'product_variants.create', 'productVariant.create', 'product-variant.create', 'create product variants']);
    }

    public function update(User $user, ProductVariant $productVariant): bool
    {
        return $this->canAny($user, ['product-variants.update', 'product_variants.update', 'productVariant.update', 'product-variant.update', 'edit product variants', 'update product variants']);
    }

    public function delete(User $user, ProductVariant $productVariant): bool
    {
        return $this->canAny($user, ['product-variants.delete', 'product_variants.delete', 'productVariant.delete', 'product-variant.delete', 'delete product variants']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['product-variants.delete', 'product_variants.delete', 'productVariant.delete', 'product-variant.delete', 'delete product variants']);
    }

    public function forceDelete(User $user, ProductVariant $productVariant): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, ProductVariant $productVariant): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, ProductVariant $productVariant): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['product-variants.update', 'product_variants.update', 'productVariant.update', 'product-variant.update', 'edit product variants', 'update product variants']);
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
