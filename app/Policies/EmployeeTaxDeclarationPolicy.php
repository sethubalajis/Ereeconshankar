<?php

namespace App\Policies;

use App\Models\EmployeeTaxDeclaration;
use App\Models\User;

class EmployeeTaxDeclarationPolicy
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
        return $this->canAny($user, ['employee-tax-declarations.view', 'employee_tax_declarations.view', 'employeeTaxDeclaration.view', 'employee-tax-declaration.view', 'view employee tax declarations']);
    }

    public function view(User $user, EmployeeTaxDeclaration $record): bool
    {
        return $this->canAny($user, ['employee-tax-declarations.view', 'employee_tax_declarations.view', 'employeeTaxDeclaration.view', 'employee-tax-declaration.view', 'view employee tax declarations']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['employee-tax-declarations.create', 'employee_tax_declarations.create', 'employeeTaxDeclaration.create', 'employee-tax-declaration.create', 'create employee tax declarations']);
    }

    public function update(User $user, EmployeeTaxDeclaration $record): bool
    {
        return $this->canAny($user, ['employee-tax-declarations.update', 'employee_tax_declarations.update', 'employeeTaxDeclaration.update', 'employee-tax-declaration.update', 'edit employee tax declarations', 'update employee tax declarations']);
    }

    public function delete(User $user, EmployeeTaxDeclaration $record): bool
    {
        return $this->canAny($user, ['employee-tax-declarations.delete', 'employee_tax_declarations.delete', 'employeeTaxDeclaration.delete', 'employee-tax-declaration.delete', 'delete employee tax declarations']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['employee-tax-declarations.delete', 'employee_tax_declarations.delete', 'employeeTaxDeclaration.delete', 'employee-tax-declaration.delete', 'delete employee tax declarations']);
    }

    public function forceDelete(User $user, EmployeeTaxDeclaration $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, EmployeeTaxDeclaration $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, EmployeeTaxDeclaration $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['employee-tax-declarations.update', 'employee_tax_declarations.update', 'employeeTaxDeclaration.update', 'employee-tax-declaration.update', 'edit employee tax declarations', 'update employee tax declarations']);
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