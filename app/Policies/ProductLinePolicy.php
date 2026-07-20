<?php

namespace App\Policies;

use App\Models\ProductLine;
use App\Models\User;

class ProductLinePolicy
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
        return $this->canAny($user, ['product-lines.view', 'product_lines.view', 'productLine.view', 'product-line.view', 'view product lines']);
    }

    public function view(User $user, ProductLine $record): bool
    {
        return $this->canAny($user, ['product-lines.view', 'product_lines.view', 'productLine.view', 'product-line.view', 'view product lines']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['product-lines.create', 'product_lines.create', 'productLine.create', 'product-line.create', 'create product lines']);
    }

    public function update(User $user, ProductLine $record): bool
    {
        return $this->canAny($user, ['product-lines.update', 'product_lines.update', 'productLine.update', 'product-line.update', 'edit product lines', 'update product lines']);
    }

    public function delete(User $user, ProductLine $record): bool
    {
        return $this->canAny($user, ['product-lines.delete', 'product_lines.delete', 'productLine.delete', 'product-line.delete', 'delete product lines']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['product-lines.delete', 'product_lines.delete', 'productLine.delete', 'product-line.delete', 'delete product lines']);
    }

    public function forceDelete(User $user, ProductLine $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, ProductLine $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, ProductLine $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['product-lines.update', 'product_lines.update', 'productLine.update', 'product-line.update', 'edit product lines', 'update product lines']);
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