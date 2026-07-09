<?php

namespace App\Policies;

use App\Models\ProductBarcode;
use App\Models\User;

class ProductBarcodePolicy
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
        return $this->canAny($user, ['product-barcodes.view', 'product_barcodes.view', 'productBarcode.view', 'product-barcode.view', 'view product barcodes']);
    }

    public function view(User $user, ProductBarcode $productBarcode): bool
    {
        return $this->canAny($user, ['product-barcodes.view', 'product_barcodes.view', 'productBarcode.view', 'product-barcode.view', 'view product barcodes']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['product-barcodes.create', 'product_barcodes.create', 'productBarcode.create', 'product-barcode.create', 'create product barcodes']);
    }

    public function update(User $user, ProductBarcode $productBarcode): bool
    {
        return $this->canAny($user, ['product-barcodes.update', 'product_barcodes.update', 'productBarcode.update', 'product-barcode.update', 'edit product barcodes', 'update product barcodes']);
    }

    public function delete(User $user, ProductBarcode $productBarcode): bool
    {
        return $this->canAny($user, ['product-barcodes.delete', 'product_barcodes.delete', 'productBarcode.delete', 'product-barcode.delete', 'delete product barcodes']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['product-barcodes.delete', 'product_barcodes.delete', 'productBarcode.delete', 'product-barcode.delete', 'delete product barcodes']);
    }

    public function forceDelete(User $user, ProductBarcode $productBarcode): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, ProductBarcode $productBarcode): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, ProductBarcode $productBarcode): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['product-barcodes.update', 'product_barcodes.update', 'productBarcode.update', 'product-barcode.update', 'edit product barcodes', 'update product barcodes']);
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
