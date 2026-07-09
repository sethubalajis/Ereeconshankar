<?php

namespace App\Policies;

use App\Models\BusinessUnit;
use App\Models\User;

class BusinessUnitPolicy
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
        return $this->canAny($user, ['business-units.view', 'business_units.view', 'businessUnit.view', 'business-unit.view', 'view business units']);
    }

    public function view(User $user, BusinessUnit $businessUnit): bool
    {
        return $this->canAny($user, ['business-units.view', 'business_units.view', 'businessUnit.view', 'business-unit.view', 'view business units']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['business-units.create', 'business_units.create', 'businessUnit.create', 'business-unit.create', 'create business units']);
    }

    public function update(User $user, BusinessUnit $businessUnit): bool
    {
        return $this->canAny($user, ['business-units.update', 'business_units.update', 'businessUnit.update', 'business-unit.update', 'edit business units', 'update business units']);
    }

    public function delete(User $user, BusinessUnit $businessUnit): bool
    {
        return $this->canAny($user, ['business-units.delete', 'business_units.delete', 'businessUnit.delete', 'business-unit.delete', 'delete business units']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['business-units.delete', 'business_units.delete', 'businessUnit.delete', 'business-unit.delete', 'delete business units']);
    }

    public function forceDelete(User $user, BusinessUnit $businessUnit): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, BusinessUnit $businessUnit): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, BusinessUnit $businessUnit): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['business-units.update', 'business_units.update', 'businessUnit.update', 'business-unit.update', 'edit business units', 'update business units']);
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
