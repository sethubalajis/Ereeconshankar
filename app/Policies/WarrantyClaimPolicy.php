<?php

namespace App\Policies;

use App\Models\WarrantyClaim;
use App\Models\User;

class WarrantyClaimPolicy
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
        return $this->canAny($user, ['warranty-claims.view', 'warranty_claims.view', 'warrantyClaim.view', 'warranty-claim.view', 'view warranty claims']);
    }

    public function view(User $user, WarrantyClaim $record): bool
    {
        return $this->canAny($user, ['warranty-claims.view', 'warranty_claims.view', 'warrantyClaim.view', 'warranty-claim.view', 'view warranty claims']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['warranty-claims.create', 'warranty_claims.create', 'warrantyClaim.create', 'warranty-claim.create', 'create warranty claims']);
    }

    public function update(User $user, WarrantyClaim $record): bool
    {
        return $this->canAny($user, ['warranty-claims.update', 'warranty_claims.update', 'warrantyClaim.update', 'warranty-claim.update', 'edit warranty claims', 'update warranty claims']);
    }

    public function delete(User $user, WarrantyClaim $record): bool
    {
        return $this->canAny($user, ['warranty-claims.delete', 'warranty_claims.delete', 'warrantyClaim.delete', 'warranty-claim.delete', 'delete warranty claims']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['warranty-claims.delete', 'warranty_claims.delete', 'warrantyClaim.delete', 'warranty-claim.delete', 'delete warranty claims']);
    }

    public function forceDelete(User $user, WarrantyClaim $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, WarrantyClaim $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, WarrantyClaim $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['warranty-claims.update', 'warranty_claims.update', 'warrantyClaim.update', 'warranty-claim.update', 'edit warranty claims', 'update warranty claims']);
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