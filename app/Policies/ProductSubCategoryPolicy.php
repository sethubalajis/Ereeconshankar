<?php

namespace App\Policies;

use App\Models\ProductSubCategory;
use App\Models\User;

class ProductSubCategoryPolicy
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
        return $this->canAny($user, ['product-sub-categories.view', 'product_sub_categories.view', 'productSubCategory.view', 'product-sub-category.view', 'view product sub categories']);
    }

    public function view(User $user, ProductSubCategory $productSubCategory): bool
    {
        return $this->canAny($user, ['product-sub-categories.view', 'product_sub_categories.view', 'productSubCategory.view', 'product-sub-category.view', 'view product sub categories']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['product-sub-categories.create', 'product_sub_categories.create', 'productSubCategory.create', 'product-sub-category.create', 'create product sub categories']);
    }

    public function update(User $user, ProductSubCategory $productSubCategory): bool
    {
        return $this->canAny($user, ['product-sub-categories.update', 'product_sub_categories.update', 'productSubCategory.update', 'product-sub-category.update', 'edit product sub categories', 'update product sub categories']);
    }

    public function delete(User $user, ProductSubCategory $productSubCategory): bool
    {
        return $this->canAny($user, ['product-sub-categories.delete', 'product_sub_categories.delete', 'productSubCategory.delete', 'product-sub-category.delete', 'delete product sub categories']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['product-sub-categories.delete', 'product_sub_categories.delete', 'productSubCategory.delete', 'product-sub-category.delete', 'delete product sub categories']);
    }

    public function forceDelete(User $user, ProductSubCategory $productSubCategory): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, ProductSubCategory $productSubCategory): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, ProductSubCategory $productSubCategory): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['product-sub-categories.update', 'product_sub_categories.update', 'productSubCategory.update', 'product-sub-category.update', 'edit product sub categories', 'update product sub categories']);
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
