<?php

namespace App\Policies;

use App\Models\Shift;
use App\Models\User;

class ShiftPolicy
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
        return $this->canAny($user, ['shifts.view', 'shifts.view', 'shift.view', 'shift.view', 'view shifts']);
    }

    public function view(User $user, Shift $record): bool
    {
        return $this->canAny($user, ['shifts.view', 'shifts.view', 'shift.view', 'shift.view', 'view shifts']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['shifts.create', 'shifts.create', 'shift.create', 'shift.create', 'create shifts']);
    }

    public function update(User $user, Shift $record): bool
    {
        return $this->canAny($user, ['shifts.update', 'shifts.update', 'shift.update', 'shift.update', 'edit shifts', 'update shifts']);
    }

    public function delete(User $user, Shift $record): bool
    {
        return $this->canAny($user, ['shifts.delete', 'shifts.delete', 'shift.delete', 'shift.delete', 'delete shifts']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['shifts.delete', 'shifts.delete', 'shift.delete', 'shift.delete', 'delete shifts']);
    }

    public function forceDelete(User $user, Shift $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, Shift $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, Shift $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['shifts.update', 'shifts.update', 'shift.update', 'shift.update', 'edit shifts', 'update shifts']);
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