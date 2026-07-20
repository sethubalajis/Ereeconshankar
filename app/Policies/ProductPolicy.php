<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
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
        return $this->canAny($user, ['products.view', 'product.view', 'view products']);
    }

    public function view(User $user, Product $product): bool
    {
        return $this->canAny($user, ['products.view', 'product.view', 'view products']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['products.create', 'product.create', 'create products']);
    }

    public function update(User $user, Product $product): bool
    {
        return $this->canAny($user, ['products.update', 'product.update', 'edit products', 'update products']);
    }

    public function delete(User $user, Product $product): bool
    {
        return $this->canAny($user, ['products.delete', 'product.delete', 'delete products']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['products.delete', 'product.delete', 'delete products']);
    }

    public function forceDelete(User $user, Product $product): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, Product $product): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, Product $product): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['products.update', 'product.update', 'edit products', 'update products']);
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
