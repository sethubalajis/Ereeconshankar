<?php

namespace App\Policies;

use App\Models\SalaryStructure;
use App\Models\User;

class SalaryStructurePolicy
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
        return $this->canAny($user, ['salary-structures.view', 'salary_structures.view', 'salaryStructure.view', 'salary-structure.view', 'view salary structures']);
    }

    public function view(User $user, SalaryStructure $record): bool
    {
        return $this->canAny($user, ['salary-structures.view', 'salary_structures.view', 'salaryStructure.view', 'salary-structure.view', 'view salary structures']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['salary-structures.create', 'salary_structures.create', 'salaryStructure.create', 'salary-structure.create', 'create salary structures']);
    }

    public function update(User $user, SalaryStructure $record): bool
    {
        return $this->canAny($user, ['salary-structures.update', 'salary_structures.update', 'salaryStructure.update', 'salary-structure.update', 'edit salary structures', 'update salary structures']);
    }

    public function delete(User $user, SalaryStructure $record): bool
    {
        return $this->canAny($user, ['salary-structures.delete', 'salary_structures.delete', 'salaryStructure.delete', 'salary-structure.delete', 'delete salary structures']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['salary-structures.delete', 'salary_structures.delete', 'salaryStructure.delete', 'salary-structure.delete', 'delete salary structures']);
    }

    public function forceDelete(User $user, SalaryStructure $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, SalaryStructure $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, SalaryStructure $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['salary-structures.update', 'salary_structures.update', 'salaryStructure.update', 'salary-structure.update', 'edit salary structures', 'update salary structures']);
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