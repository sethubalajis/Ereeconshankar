<?php

namespace App\Policies;

use App\Models\TrialBalance;
use App\Models\User;

class TrialBalancePolicy
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
        return $this->canAny($user, ['trial-balances.view', 'trial_balances.view', 'trialBalance.view', 'trial-balance.view', 'view trial balances']);
    }

    public function view(User $user, TrialBalance $record): bool
    {
        return $this->canAny($user, ['trial-balances.view', 'trial_balances.view', 'trialBalance.view', 'trial-balance.view', 'view trial balances']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['trial-balances.create', 'trial_balances.create', 'trialBalance.create', 'trial-balance.create', 'create trial balances']);
    }

    public function update(User $user, TrialBalance $record): bool
    {
        return $this->canAny($user, ['trial-balances.update', 'trial_balances.update', 'trialBalance.update', 'trial-balance.update', 'edit trial balances', 'update trial balances']);
    }

    public function delete(User $user, TrialBalance $record): bool
    {
        return $this->canAny($user, ['trial-balances.delete', 'trial_balances.delete', 'trialBalance.delete', 'trial-balance.delete', 'delete trial balances']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['trial-balances.delete', 'trial_balances.delete', 'trialBalance.delete', 'trial-balance.delete', 'delete trial balances']);
    }

    public function forceDelete(User $user, TrialBalance $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, TrialBalance $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, TrialBalance $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['trial-balances.update', 'trial_balances.update', 'trialBalance.update', 'trial-balance.update', 'edit trial balances', 'update trial balances']);
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