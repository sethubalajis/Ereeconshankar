<?php

namespace App\Policies;

use App\Models\Incentive;
use App\Models\User;

class IncentivePolicy
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
        return $this->canAny($user, ['incentives.view', 'incentives.view', 'incentive.view', 'incentive.view', 'view incentives']);
    }

    public function view(User $user, Incentive $record): bool
    {
        return $this->canAny($user, ['incentives.view', 'incentives.view', 'incentive.view', 'incentive.view', 'view incentives']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['incentives.create', 'incentives.create', 'incentive.create', 'incentive.create', 'create incentives']);
    }

    public function update(User $user, Incentive $record): bool
    {
        return $this->canAny($user, ['incentives.update', 'incentives.update', 'incentive.update', 'incentive.update', 'edit incentives', 'update incentives']);
    }

    public function delete(User $user, Incentive $record): bool
    {
        return $this->canAny($user, ['incentives.delete', 'incentives.delete', 'incentive.delete', 'incentive.delete', 'delete incentives']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['incentives.delete', 'incentives.delete', 'incentive.delete', 'incentive.delete', 'delete incentives']);
    }

    public function forceDelete(User $user, Incentive $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, Incentive $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, Incentive $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['incentives.update', 'incentives.update', 'incentive.update', 'incentive.update', 'edit incentives', 'update incentives']);
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