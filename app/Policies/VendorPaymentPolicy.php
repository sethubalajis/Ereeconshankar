<?php

namespace App\Policies;

use App\Models\VendorPayment;
use App\Models\User;

class VendorPaymentPolicy
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
        return $this->canAny($user, ['vendor-payments.view', 'vendor_payments.view', 'vendorPayment.view', 'vendor-payment.view', 'view vendor payments']);
    }

    public function view(User $user, VendorPayment $record): bool
    {
        return $this->canAny($user, ['vendor-payments.view', 'vendor_payments.view', 'vendorPayment.view', 'vendor-payment.view', 'view vendor payments']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['vendor-payments.create', 'vendor_payments.create', 'vendorPayment.create', 'vendor-payment.create', 'create vendor payments']);
    }

    public function update(User $user, VendorPayment $record): bool
    {
        return $this->canAny($user, ['vendor-payments.update', 'vendor_payments.update', 'vendorPayment.update', 'vendor-payment.update', 'edit vendor payments', 'update vendor payments']);
    }

    public function delete(User $user, VendorPayment $record): bool
    {
        return $this->canAny($user, ['vendor-payments.delete', 'vendor_payments.delete', 'vendorPayment.delete', 'vendor-payment.delete', 'delete vendor payments']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['vendor-payments.delete', 'vendor_payments.delete', 'vendorPayment.delete', 'vendor-payment.delete', 'delete vendor payments']);
    }

    public function forceDelete(User $user, VendorPayment $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, VendorPayment $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, VendorPayment $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['vendor-payments.update', 'vendor_payments.update', 'vendorPayment.update', 'vendor-payment.update', 'edit vendor payments', 'update vendor payments']);
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