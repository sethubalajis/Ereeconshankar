<?php

namespace App\Policies;

use App\Models\HsnSacCode;
use App\Models\User;

class HsnSacCodePolicy
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
        return $this->canAny($user, ['hsn-sac-codes.view', 'hsn_sac_codes.view', 'hsnSacCode.view', 'hsn-sac-code.view', 'view hsn sac codes']);
    }

    public function view(User $user, HsnSacCode $record): bool
    {
        return $this->canAny($user, ['hsn-sac-codes.view', 'hsn_sac_codes.view', 'hsnSacCode.view', 'hsn-sac-code.view', 'view hsn sac codes']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['hsn-sac-codes.create', 'hsn_sac_codes.create', 'hsnSacCode.create', 'hsn-sac-code.create', 'create hsn sac codes']);
    }

    public function update(User $user, HsnSacCode $record): bool
    {
        return $this->canAny($user, ['hsn-sac-codes.update', 'hsn_sac_codes.update', 'hsnSacCode.update', 'hsn-sac-code.update', 'edit hsn sac codes', 'update hsn sac codes']);
    }

    public function delete(User $user, HsnSacCode $record): bool
    {
        return $this->canAny($user, ['hsn-sac-codes.delete', 'hsn_sac_codes.delete', 'hsnSacCode.delete', 'hsn-sac-code.delete', 'delete hsn sac codes']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['hsn-sac-codes.delete', 'hsn_sac_codes.delete', 'hsnSacCode.delete', 'hsn-sac-code.delete', 'delete hsn sac codes']);
    }

    public function forceDelete(User $user, HsnSacCode $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, HsnSacCode $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, HsnSacCode $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['hsn-sac-codes.update', 'hsn_sac_codes.update', 'hsnSacCode.update', 'hsn-sac-code.update', 'edit hsn sac codes', 'update hsn sac codes']);
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