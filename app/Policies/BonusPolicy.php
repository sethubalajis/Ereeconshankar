<?php

namespace App\Policies;

use App\Models\Bonus;
use App\Models\User;

class BonusPolicy
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
        return $this->canAny($user, ['bonuses.view', 'bonuses.view', 'bonus.view', 'bonus.view', 'view bonuses']);
    }

    public function view(User $user, Bonus $record): bool
    {
        return $this->canAny($user, ['bonuses.view', 'bonuses.view', 'bonus.view', 'bonus.view', 'view bonuses']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['bonuses.create', 'bonuses.create', 'bonus.create', 'bonus.create', 'create bonuses']);
    }

    public function update(User $user, Bonus $record): bool
    {
        return $this->canAny($user, ['bonuses.update', 'bonuses.update', 'bonus.update', 'bonus.update', 'edit bonuses', 'update bonuses']);
    }

    public function delete(User $user, Bonus $record): bool
    {
        return $this->canAny($user, ['bonuses.delete', 'bonuses.delete', 'bonus.delete', 'bonus.delete', 'delete bonuses']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['bonuses.delete', 'bonuses.delete', 'bonus.delete', 'bonus.delete', 'delete bonuses']);
    }

    public function forceDelete(User $user, Bonus $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, Bonus $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, Bonus $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['bonuses.update', 'bonuses.update', 'bonus.update', 'bonus.update', 'edit bonuses', 'update bonuses']);
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