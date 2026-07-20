<?php

namespace App\Policies;

use App\Models\VendorContact;
use App\Models\User;

class VendorContactPolicy
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
        return $this->canAny($user, ['vendor-contacts.view', 'vendor_contacts.view', 'vendorContact.view', 'vendor-contact.view', 'view vendor contacts']);
    }

    public function view(User $user, VendorContact $record): bool
    {
        return $this->canAny($user, ['vendor-contacts.view', 'vendor_contacts.view', 'vendorContact.view', 'vendor-contact.view', 'view vendor contacts']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['vendor-contacts.create', 'vendor_contacts.create', 'vendorContact.create', 'vendor-contact.create', 'create vendor contacts']);
    }

    public function update(User $user, VendorContact $record): bool
    {
        return $this->canAny($user, ['vendor-contacts.update', 'vendor_contacts.update', 'vendorContact.update', 'vendor-contact.update', 'edit vendor contacts', 'update vendor contacts']);
    }

    public function delete(User $user, VendorContact $record): bool
    {
        return $this->canAny($user, ['vendor-contacts.delete', 'vendor_contacts.delete', 'vendorContact.delete', 'vendor-contact.delete', 'delete vendor contacts']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['vendor-contacts.delete', 'vendor_contacts.delete', 'vendorContact.delete', 'vendor-contact.delete', 'delete vendor contacts']);
    }

    public function forceDelete(User $user, VendorContact $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, VendorContact $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, VendorContact $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['vendor-contacts.update', 'vendor_contacts.update', 'vendorContact.update', 'vendor-contact.update', 'edit vendor contacts', 'update vendor contacts']);
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