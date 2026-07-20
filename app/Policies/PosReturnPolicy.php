<?php

namespace App\Policies;

use App\Models\PosReturn;
use App\Models\User;

class PosReturnPolicy
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
        return $this->canAny($user, ['pos-returns.view', 'pos_returns.view', 'posReturn.view', 'pos-return.view', 'view pos returns']);
    }

    public function view(User $user, PosReturn $record): bool
    {
        return $this->canAny($user, ['pos-returns.view', 'pos_returns.view', 'posReturn.view', 'pos-return.view', 'view pos returns']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['pos-returns.create', 'pos_returns.create', 'posReturn.create', 'pos-return.create', 'create pos returns']);
    }

    public function update(User $user, PosReturn $record): bool
    {
        return $this->canAny($user, ['pos-returns.update', 'pos_returns.update', 'posReturn.update', 'pos-return.update', 'edit pos returns', 'update pos returns']);
    }

    public function delete(User $user, PosReturn $record): bool
    {
        return $this->canAny($user, ['pos-returns.delete', 'pos_returns.delete', 'posReturn.delete', 'pos-return.delete', 'delete pos returns']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['pos-returns.delete', 'pos_returns.delete', 'posReturn.delete', 'pos-return.delete', 'delete pos returns']);
    }

    public function forceDelete(User $user, PosReturn $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, PosReturn $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, PosReturn $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['pos-returns.update', 'pos_returns.update', 'posReturn.update', 'pos-return.update', 'edit pos returns', 'update pos returns']);
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