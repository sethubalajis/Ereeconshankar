<?php

namespace App\Policies;

use App\Models\LoyaltyProgram;
use App\Models\User;

class LoyaltyProgramPolicy
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
        return $this->canAny($user, ['loyalty-programs.view', 'loyalty_programs.view', 'loyaltyProgram.view', 'loyalty-program.view', 'view loyalty programs']);
    }

    public function view(User $user, LoyaltyProgram $record): bool
    {
        return $this->canAny($user, ['loyalty-programs.view', 'loyalty_programs.view', 'loyaltyProgram.view', 'loyalty-program.view', 'view loyalty programs']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['loyalty-programs.create', 'loyalty_programs.create', 'loyaltyProgram.create', 'loyalty-program.create', 'create loyalty programs']);
    }

    public function update(User $user, LoyaltyProgram $record): bool
    {
        return $this->canAny($user, ['loyalty-programs.update', 'loyalty_programs.update', 'loyaltyProgram.update', 'loyalty-program.update', 'edit loyalty programs', 'update loyalty programs']);
    }

    public function delete(User $user, LoyaltyProgram $record): bool
    {
        return $this->canAny($user, ['loyalty-programs.delete', 'loyalty_programs.delete', 'loyaltyProgram.delete', 'loyalty-program.delete', 'delete loyalty programs']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['loyalty-programs.delete', 'loyalty_programs.delete', 'loyaltyProgram.delete', 'loyalty-program.delete', 'delete loyalty programs']);
    }

    public function forceDelete(User $user, LoyaltyProgram $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, LoyaltyProgram $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, LoyaltyProgram $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['loyalty-programs.update', 'loyalty_programs.update', 'loyaltyProgram.update', 'loyalty-program.update', 'edit loyalty programs', 'update loyalty programs']);
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