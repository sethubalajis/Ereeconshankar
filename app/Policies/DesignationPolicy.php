<?php

namespace App\Policies;

use App\Models\Designation;
use App\Models\User;

class DesignationPolicy
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
        return $this->canAny($user, ['designations.view', 'designations.view', 'designation.view', 'designation.view', 'view designations']);
    }

    public function view(User $user, Designation $record): bool
    {
        return $this->canAny($user, ['designations.view', 'designations.view', 'designation.view', 'designation.view', 'view designations']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['designations.create', 'designations.create', 'designation.create', 'designation.create', 'create designations']);
    }

    public function update(User $user, Designation $record): bool
    {
        return $this->canAny($user, ['designations.update', 'designations.update', 'designation.update', 'designation.update', 'edit designations', 'update designations']);
    }

    public function delete(User $user, Designation $record): bool
    {
        return $this->canAny($user, ['designations.delete', 'designations.delete', 'designation.delete', 'designation.delete', 'delete designations']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['designations.delete', 'designations.delete', 'designation.delete', 'designation.delete', 'delete designations']);
    }

    public function forceDelete(User $user, Designation $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, Designation $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, Designation $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['designations.update', 'designations.update', 'designation.update', 'designation.update', 'edit designations', 'update designations']);
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