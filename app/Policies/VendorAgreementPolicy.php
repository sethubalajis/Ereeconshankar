<?php

namespace App\Policies;

use App\Models\VendorAgreement;
use App\Models\User;

class VendorAgreementPolicy
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
        return $this->canAny($user, ['vendor-agreements.view', 'vendor_agreements.view', 'vendorAgreement.view', 'vendor-agreement.view', 'view vendor agreements']);
    }

    public function view(User $user, VendorAgreement $record): bool
    {
        return $this->canAny($user, ['vendor-agreements.view', 'vendor_agreements.view', 'vendorAgreement.view', 'vendor-agreement.view', 'view vendor agreements']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['vendor-agreements.create', 'vendor_agreements.create', 'vendorAgreement.create', 'vendor-agreement.create', 'create vendor agreements']);
    }

    public function update(User $user, VendorAgreement $record): bool
    {
        return $this->canAny($user, ['vendor-agreements.update', 'vendor_agreements.update', 'vendorAgreement.update', 'vendor-agreement.update', 'edit vendor agreements', 'update vendor agreements']);
    }

    public function delete(User $user, VendorAgreement $record): bool
    {
        return $this->canAny($user, ['vendor-agreements.delete', 'vendor_agreements.delete', 'vendorAgreement.delete', 'vendor-agreement.delete', 'delete vendor agreements']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['vendor-agreements.delete', 'vendor_agreements.delete', 'vendorAgreement.delete', 'vendor-agreement.delete', 'delete vendor agreements']);
    }

    public function forceDelete(User $user, VendorAgreement $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, VendorAgreement $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, VendorAgreement $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['vendor-agreements.update', 'vendor_agreements.update', 'vendorAgreement.update', 'vendor-agreement.update', 'edit vendor agreements', 'update vendor agreements']);
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