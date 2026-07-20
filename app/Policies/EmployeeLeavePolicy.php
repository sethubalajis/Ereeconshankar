<?php

namespace App\Policies;

use App\Models\EmployeeLeave;
use App\Models\User;

class EmployeeLeavePolicy
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
        return $this->canAny($user, ['employee-leaves.view', 'employee_leaves.view', 'employeeLeave.view', 'employee-leave.view', 'view employee leaves']);
    }

    public function view(User $user, EmployeeLeave $record): bool
    {
        return $this->canAny($user, ['employee-leaves.view', 'employee_leaves.view', 'employeeLeave.view', 'employee-leave.view', 'view employee leaves']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['employee-leaves.create', 'employee_leaves.create', 'employeeLeave.create', 'employee-leave.create', 'create employee leaves']);
    }

    public function update(User $user, EmployeeLeave $record): bool
    {
        return $this->canAny($user, ['employee-leaves.update', 'employee_leaves.update', 'employeeLeave.update', 'employee-leave.update', 'edit employee leaves', 'update employee leaves']);
    }

    public function delete(User $user, EmployeeLeave $record): bool
    {
        return $this->canAny($user, ['employee-leaves.delete', 'employee_leaves.delete', 'employeeLeave.delete', 'employee-leave.delete', 'delete employee leaves']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['employee-leaves.delete', 'employee_leaves.delete', 'employeeLeave.delete', 'employee-leave.delete', 'delete employee leaves']);
    }

    public function forceDelete(User $user, EmployeeLeave $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, EmployeeLeave $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, EmployeeLeave $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['employee-leaves.update', 'employee_leaves.update', 'employeeLeave.update', 'employee-leave.update', 'edit employee leaves', 'update employee leaves']);
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