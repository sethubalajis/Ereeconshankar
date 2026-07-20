<?php

namespace App\Policies;

use App\Models\EmployeeTaxDeclarationItem;
use App\Models\User;

class EmployeeTaxDeclarationItemPolicy
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
        return $this->canAny($user, ['employee-tax-declaration-items.view', 'employee_tax_declaration_items.view', 'employeeTaxDeclarationItem.view', 'employee-tax-declaration-item.view', 'view employee tax declaration items']);
    }

    public function view(User $user, EmployeeTaxDeclarationItem $record): bool
    {
        return $this->canAny($user, ['employee-tax-declaration-items.view', 'employee_tax_declaration_items.view', 'employeeTaxDeclarationItem.view', 'employee-tax-declaration-item.view', 'view employee tax declaration items']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['employee-tax-declaration-items.create', 'employee_tax_declaration_items.create', 'employeeTaxDeclarationItem.create', 'employee-tax-declaration-item.create', 'create employee tax declaration items']);
    }

    public function update(User $user, EmployeeTaxDeclarationItem $record): bool
    {
        return $this->canAny($user, ['employee-tax-declaration-items.update', 'employee_tax_declaration_items.update', 'employeeTaxDeclarationItem.update', 'employee-tax-declaration-item.update', 'edit employee tax declaration items', 'update employee tax declaration items']);
    }

    public function delete(User $user, EmployeeTaxDeclarationItem $record): bool
    {
        return $this->canAny($user, ['employee-tax-declaration-items.delete', 'employee_tax_declaration_items.delete', 'employeeTaxDeclarationItem.delete', 'employee-tax-declaration-item.delete', 'delete employee tax declaration items']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['employee-tax-declaration-items.delete', 'employee_tax_declaration_items.delete', 'employeeTaxDeclarationItem.delete', 'employee-tax-declaration-item.delete', 'delete employee tax declaration items']);
    }

    public function forceDelete(User $user, EmployeeTaxDeclarationItem $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, EmployeeTaxDeclarationItem $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, EmployeeTaxDeclarationItem $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['employee-tax-declaration-items.update', 'employee_tax_declaration_items.update', 'employeeTaxDeclarationItem.update', 'employee-tax-declaration-item.update', 'edit employee tax declaration items', 'update employee tax declaration items']);
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