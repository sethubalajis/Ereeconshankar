<?php

namespace App\Policies;

use App\Models\Session;
use App\Models\User;

class SessionPolicy
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
        return $this->canAny($user, ['sessions.view', 'sessions.view', 'session.view', 'session.view', 'view sessions']);
    }

    public function view(User $user, Session $record): bool
    {
        return $this->canAny($user, ['sessions.view', 'sessions.view', 'session.view', 'session.view', 'view sessions']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['sessions.create', 'sessions.create', 'session.create', 'session.create', 'create sessions']);
    }

    public function update(User $user, Session $record): bool
    {
        return $this->canAny($user, ['sessions.update', 'sessions.update', 'session.update', 'session.update', 'edit sessions', 'update sessions']);
    }

    public function delete(User $user, Session $record): bool
    {
        return $this->canAny($user, ['sessions.delete', 'sessions.delete', 'session.delete', 'session.delete', 'delete sessions']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['sessions.delete', 'sessions.delete', 'session.delete', 'session.delete', 'delete sessions']);
    }

    public function forceDelete(User $user, Session $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, Session $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, Session $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['sessions.update', 'sessions.update', 'session.update', 'session.update', 'edit sessions', 'update sessions']);
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