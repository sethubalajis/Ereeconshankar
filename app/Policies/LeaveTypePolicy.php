<?php

namespace App\Policies;

use App\Models\LeaveType;
use App\Models\User;

class LeaveTypePolicy
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
        return $this->canAny($user, ['leave-types.view', 'leave_types.view', 'leaveType.view', 'leave-type.view', 'view leave types']);
    }

    public function view(User $user, LeaveType $record): bool
    {
        return $this->canAny($user, ['leave-types.view', 'leave_types.view', 'leaveType.view', 'leave-type.view', 'view leave types']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['leave-types.create', 'leave_types.create', 'leaveType.create', 'leave-type.create', 'create leave types']);
    }

    public function update(User $user, LeaveType $record): bool
    {
        return $this->canAny($user, ['leave-types.update', 'leave_types.update', 'leaveType.update', 'leave-type.update', 'edit leave types', 'update leave types']);
    }

    public function delete(User $user, LeaveType $record): bool
    {
        return $this->canAny($user, ['leave-types.delete', 'leave_types.delete', 'leaveType.delete', 'leave-type.delete', 'delete leave types']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['leave-types.delete', 'leave_types.delete', 'leaveType.delete', 'leave-type.delete', 'delete leave types']);
    }

    public function forceDelete(User $user, LeaveType $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, LeaveType $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, LeaveType $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['leave-types.update', 'leave_types.update', 'leaveType.update', 'leave-type.update', 'edit leave types', 'update leave types']);
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