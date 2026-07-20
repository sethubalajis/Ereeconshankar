<?php

namespace App\Policies;

use App\Models\HsnCode;
use App\Models\User;

class HsnCodePolicy
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
        return $this->canAny($user, ['hsn-codes.view', 'hsn_codes.view', 'hsnCode.view', 'hsn-code.view', 'view hsn codes']);
    }

    public function view(User $user, HsnCode $hsnCode): bool
    {
        return $this->canAny($user, ['hsn-codes.view', 'hsn_codes.view', 'hsnCode.view', 'hsn-code.view', 'view hsn codes']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['hsn-codes.create', 'hsn_codes.create', 'hsnCode.create', 'hsn-code.create', 'create hsn codes']);
    }

    public function update(User $user, HsnCode $hsnCode): bool
    {
        return $this->canAny($user, ['hsn-codes.update', 'hsn_codes.update', 'hsnCode.update', 'hsn-code.update', 'edit hsn codes', 'update hsn codes']);
    }

    public function delete(User $user, HsnCode $hsnCode): bool
    {
        return $this->canAny($user, ['hsn-codes.delete', 'hsn_codes.delete', 'hsnCode.delete', 'hsn-code.delete', 'delete hsn codes']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['hsn-codes.delete', 'hsn_codes.delete', 'hsnCode.delete', 'hsn-code.delete', 'delete hsn codes']);
    }

    public function forceDelete(User $user, HsnCode $hsnCode): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, HsnCode $hsnCode): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, HsnCode $hsnCode): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['hsn-codes.update', 'hsn_codes.update', 'hsnCode.update', 'hsn-code.update', 'edit hsn codes', 'update hsn codes']);
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
