<?php

namespace App\Policies;

use App\Models\Attendance;
use App\Models\User;

class AttendancePolicy
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
        return $this->canAny($user, ['attendances.view', 'attendances.view', 'attendance.view', 'attendance.view', 'view attendances']);
    }

    public function view(User $user, Attendance $record): bool
    {
        return $this->canAny($user, ['attendances.view', 'attendances.view', 'attendance.view', 'attendance.view', 'view attendances']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['attendances.create', 'attendances.create', 'attendance.create', 'attendance.create', 'create attendances']);
    }

    public function update(User $user, Attendance $record): bool
    {
        return $this->canAny($user, ['attendances.update', 'attendances.update', 'attendance.update', 'attendance.update', 'edit attendances', 'update attendances']);
    }

    public function delete(User $user, Attendance $record): bool
    {
        return $this->canAny($user, ['attendances.delete', 'attendances.delete', 'attendance.delete', 'attendance.delete', 'delete attendances']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['attendances.delete', 'attendances.delete', 'attendance.delete', 'attendance.delete', 'delete attendances']);
    }

    public function forceDelete(User $user, Attendance $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, Attendance $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, Attendance $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['attendances.update', 'attendances.update', 'attendance.update', 'attendance.update', 'edit attendances', 'update attendances']);
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