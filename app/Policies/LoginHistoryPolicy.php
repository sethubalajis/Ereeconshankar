<?php

namespace App\Policies;

use App\Models\LoginHistory;
use App\Models\User;

class LoginHistoryPolicy
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
        return $this->canAny($user, ['login-histories.view', 'login_histories.view', 'loginHistory.view', 'login-history.view', 'view login histories']);
    }

    public function view(User $user, LoginHistory $record): bool
    {
        return $this->canAny($user, ['login-histories.view', 'login_histories.view', 'loginHistory.view', 'login-history.view', 'view login histories']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['login-histories.create', 'login_histories.create', 'loginHistory.create', 'login-history.create', 'create login histories']);
    }

    public function update(User $user, LoginHistory $record): bool
    {
        return $this->canAny($user, ['login-histories.update', 'login_histories.update', 'loginHistory.update', 'login-history.update', 'edit login histories', 'update login histories']);
    }

    public function delete(User $user, LoginHistory $record): bool
    {
        return $this->canAny($user, ['login-histories.delete', 'login_histories.delete', 'loginHistory.delete', 'login-history.delete', 'delete login histories']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['login-histories.delete', 'login_histories.delete', 'loginHistory.delete', 'login-history.delete', 'delete login histories']);
    }

    public function forceDelete(User $user, LoginHistory $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, LoginHistory $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, LoginHistory $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['login-histories.update', 'login_histories.update', 'loginHistory.update', 'login-history.update', 'edit login histories', 'update login histories']);
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