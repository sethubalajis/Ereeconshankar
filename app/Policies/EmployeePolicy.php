<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\User;

class EmployeePolicy
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
        return $this->canAny($user, ['employees.view', 'employees.view', 'employee.view', 'employee.view', 'view employees']);
    }

    public function view(User $user, Employee $record): bool
    {
        return $this->canAny($user, ['employees.view', 'employees.view', 'employee.view', 'employee.view', 'view employees']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['employees.create', 'employees.create', 'employee.create', 'employee.create', 'create employees']);
    }

    public function update(User $user, Employee $record): bool
    {
        return $this->canAny($user, ['employees.update', 'employees.update', 'employee.update', 'employee.update', 'edit employees', 'update employees']);
    }

    public function delete(User $user, Employee $record): bool
    {
        return $this->canAny($user, ['employees.delete', 'employees.delete', 'employee.delete', 'employee.delete', 'delete employees']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['employees.delete', 'employees.delete', 'employee.delete', 'employee.delete', 'delete employees']);
    }

    public function forceDelete(User $user, Employee $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, Employee $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, Employee $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['employees.update', 'employees.update', 'employee.update', 'employee.update', 'edit employees', 'update employees']);
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