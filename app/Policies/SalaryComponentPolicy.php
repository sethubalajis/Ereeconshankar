<?php

namespace App\Policies;

use App\Models\SalaryComponent;
use App\Models\User;

class SalaryComponentPolicy
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
        return $this->canAny($user, ['salary-components.view', 'salary_components.view', 'salaryComponent.view', 'salary-component.view', 'view salary components']);
    }

    public function view(User $user, SalaryComponent $record): bool
    {
        return $this->canAny($user, ['salary-components.view', 'salary_components.view', 'salaryComponent.view', 'salary-component.view', 'view salary components']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['salary-components.create', 'salary_components.create', 'salaryComponent.create', 'salary-component.create', 'create salary components']);
    }

    public function update(User $user, SalaryComponent $record): bool
    {
        return $this->canAny($user, ['salary-components.update', 'salary_components.update', 'salaryComponent.update', 'salary-component.update', 'edit salary components', 'update salary components']);
    }

    public function delete(User $user, SalaryComponent $record): bool
    {
        return $this->canAny($user, ['salary-components.delete', 'salary_components.delete', 'salaryComponent.delete', 'salary-component.delete', 'delete salary components']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['salary-components.delete', 'salary_components.delete', 'salaryComponent.delete', 'salary-component.delete', 'delete salary components']);
    }

    public function forceDelete(User $user, SalaryComponent $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, SalaryComponent $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, SalaryComponent $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['salary-components.update', 'salary_components.update', 'salaryComponent.update', 'salary-component.update', 'edit salary components', 'update salary components']);
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