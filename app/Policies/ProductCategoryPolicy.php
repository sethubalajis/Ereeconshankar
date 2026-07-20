<?php

namespace App\Policies;

use App\Models\ProductCategory;
use App\Models\User;

class ProductCategoryPolicy
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
        return $this->canAny($user, ['product-categories.view', 'product_categories.view', 'productCategory.view', 'product-category.view', 'view product categories']);
    }

    public function view(User $user, ProductCategory $productCategory): bool
    {
        return $this->canAny($user, ['product-categories.view', 'product_categories.view', 'productCategory.view', 'product-category.view', 'view product categories']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['product-categories.create', 'product_categories.create', 'productCategory.create', 'product-category.create', 'create product categories']);
    }

    public function update(User $user, ProductCategory $productCategory): bool
    {
        return $this->canAny($user, ['product-categories.update', 'product_categories.update', 'productCategory.update', 'product-category.update', 'edit product categories', 'update product categories']);
    }

    public function delete(User $user, ProductCategory $productCategory): bool
    {
        return $this->canAny($user, ['product-categories.delete', 'product_categories.delete', 'productCategory.delete', 'product-category.delete', 'delete product categories']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['product-categories.delete', 'product_categories.delete', 'productCategory.delete', 'product-category.delete', 'delete product categories']);
    }

    public function forceDelete(User $user, ProductCategory $productCategory): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, ProductCategory $productCategory): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, ProductCategory $productCategory): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['product-categories.update', 'product_categories.update', 'productCategory.update', 'product-category.update', 'edit product categories', 'update product categories']);
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
