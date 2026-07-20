<?php

namespace App\Policies;

use App\Models\EmployeeSalaryAssignment;
use App\Models\User;

class EmployeeSalaryAssignmentPolicy
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
        return $this->canAny($user, ['employee-salary-assignments.view', 'employee_salary_assignments.view', 'employeeSalaryAssignment.view', 'employee-salary-assignment.view', 'view employee salary assignments']);
    }

    public function view(User $user, EmployeeSalaryAssignment $record): bool
    {
        return $this->canAny($user, ['employee-salary-assignments.view', 'employee_salary_assignments.view', 'employeeSalaryAssignment.view', 'employee-salary-assignment.view', 'view employee salary assignments']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['employee-salary-assignments.create', 'employee_salary_assignments.create', 'employeeSalaryAssignment.create', 'employee-salary-assignment.create', 'create employee salary assignments']);
    }

    public function update(User $user, EmployeeSalaryAssignment $record): bool
    {
        return $this->canAny($user, ['employee-salary-assignments.update', 'employee_salary_assignments.update', 'employeeSalaryAssignment.update', 'employee-salary-assignment.update', 'edit employee salary assignments', 'update employee salary assignments']);
    }

    public function delete(User $user, EmployeeSalaryAssignment $record): bool
    {
        return $this->canAny($user, ['employee-salary-assignments.delete', 'employee_salary_assignments.delete', 'employeeSalaryAssignment.delete', 'employee-salary-assignment.delete', 'delete employee salary assignments']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['employee-salary-assignments.delete', 'employee_salary_assignments.delete', 'employeeSalaryAssignment.delete', 'employee-salary-assignment.delete', 'delete employee salary assignments']);
    }

    public function forceDelete(User $user, EmployeeSalaryAssignment $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, EmployeeSalaryAssignment $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, EmployeeSalaryAssignment $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['employee-salary-assignments.update', 'employee_salary_assignments.update', 'employeeSalaryAssignment.update', 'employee-salary-assignment.update', 'edit employee salary assignments', 'update employee salary assignments']);
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