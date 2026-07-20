<?php

namespace App\Policies;

use App\Models\Deduction;
use App\Models\User;

class DeductionPolicy
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
        return $this->canAny($user, ['deductions.view', 'deductions.view', 'deduction.view', 'deduction.view', 'view deductions']);
    }

    public function view(User $user, Deduction $record): bool
    {
        return $this->canAny($user, ['deductions.view', 'deductions.view', 'deduction.view', 'deduction.view', 'view deductions']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['deductions.create', 'deductions.create', 'deduction.create', 'deduction.create', 'create deductions']);
    }

    public function update(User $user, Deduction $record): bool
    {
        return $this->canAny($user, ['deductions.update', 'deductions.update', 'deduction.update', 'deduction.update', 'edit deductions', 'update deductions']);
    }

    public function delete(User $user, Deduction $record): bool
    {
        return $this->canAny($user, ['deductions.delete', 'deductions.delete', 'deduction.delete', 'deduction.delete', 'delete deductions']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['deductions.delete', 'deductions.delete', 'deduction.delete', 'deduction.delete', 'delete deductions']);
    }

    public function forceDelete(User $user, Deduction $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, Deduction $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, Deduction $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['deductions.update', 'deductions.update', 'deduction.update', 'deduction.update', 'edit deductions', 'update deductions']);
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