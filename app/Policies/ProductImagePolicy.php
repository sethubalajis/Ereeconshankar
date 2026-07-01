<?php

namespace App\Policies;

use App\Models\ProductImage;
use App\Models\User;

class ProductImagePolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if ($user->hasRole('Super Admin') || $user->hasRole('Admin')) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $this->canAny($user, ['product-images.view', 'product_images.view', 'productImage.view', 'product-image.view', 'view product images']);
    }

    public function view(User $user, ProductImage $productImage): bool
    {
        return $this->canAny($user, ['product-images.view', 'product_images.view', 'productImage.view', 'product-image.view', 'view product images']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['product-images.create', 'product_images.create', 'productImage.create', 'product-image.create', 'create product images']);
    }

    public function update(User $user, ProductImage $productImage): bool
    {
        return $this->canAny($user, ['product-images.update', 'product_images.update', 'productImage.update', 'product-image.update', 'edit product images', 'update product images']);
    }

    public function delete(User $user, ProductImage $productImage): bool
    {
        return $this->canAny($user, ['product-images.delete', 'product_images.delete', 'productImage.delete', 'product-image.delete', 'delete product images']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['product-images.delete', 'product_images.delete', 'productImage.delete', 'product-image.delete', 'delete product images']);
    }

    public function forceDelete(User $user, ProductImage $productImage): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, ProductImage $productImage): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, ProductImage $productImage): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['product-images.update', 'product_images.update', 'productImage.update', 'product-image.update', 'edit product images', 'update product images']);
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
