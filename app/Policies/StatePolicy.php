<?php

namespace App\Policies;

use App\Models\State;
use App\Models\User;

class StatePolicy
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
        return $this->canAny($user, ['states.view', 'state.view', 'view states']);
    }

    public function view(User $user, State $state): bool
    {
        return $this->canAny($user, ['states.view', 'state.view', 'view states']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['states.create', 'state.create', 'create states']);
    }

    public function update(User $user, State $state): bool
    {
        return $this->canAny($user, ['states.update', 'state.update', 'edit states', 'update states']);
    }

    public function delete(User $user, State $state): bool
    {
        return $this->canAny($user, ['states.delete', 'state.delete', 'delete states']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['states.delete', 'state.delete', 'delete states']);
    }

    public function forceDelete(User $user, State $state): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, State $state): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, State $state): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['states.update', 'state.update', 'edit states', 'update states']);
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
