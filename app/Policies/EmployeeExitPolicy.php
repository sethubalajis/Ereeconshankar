<?php

namespace App\Policies;

use App\Models\EmployeeExit;
use App\Models\User;

class EmployeeExitPolicy
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
        return $this->canAny($user, ['employee-exits.view', 'employee_exits.view', 'employeeExit.view', 'employee-exit.view', 'view employee exits']);
    }

    public function view(User $user, EmployeeExit $record): bool
    {
        return $this->canAny($user, ['employee-exits.view', 'employee_exits.view', 'employeeExit.view', 'employee-exit.view', 'view employee exits']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['employee-exits.create', 'employee_exits.create', 'employeeExit.create', 'employee-exit.create', 'create employee exits']);
    }

    public function update(User $user, EmployeeExit $record): bool
    {
        return $this->canAny($user, ['employee-exits.update', 'employee_exits.update', 'employeeExit.update', 'employee-exit.update', 'edit employee exits', 'update employee exits']);
    }

    public function delete(User $user, EmployeeExit $record): bool
    {
        return $this->canAny($user, ['employee-exits.delete', 'employee_exits.delete', 'employeeExit.delete', 'employee-exit.delete', 'delete employee exits']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['employee-exits.delete', 'employee_exits.delete', 'employeeExit.delete', 'employee-exit.delete', 'delete employee exits']);
    }

    public function forceDelete(User $user, EmployeeExit $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, EmployeeExit $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, EmployeeExit $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['employee-exits.update', 'employee_exits.update', 'employeeExit.update', 'employee-exit.update', 'edit employee exits', 'update employee exits']);
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