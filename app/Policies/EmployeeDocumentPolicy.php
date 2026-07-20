<?php

namespace App\Policies;

use App\Models\EmployeeDocument;
use App\Models\User;

class EmployeeDocumentPolicy
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
        return $this->canAny($user, ['employee-documents.view', 'employee_documents.view', 'employeeDocument.view', 'employee-document.view', 'view employee documents']);
    }

    public function view(User $user, EmployeeDocument $record): bool
    {
        return $this->canAny($user, ['employee-documents.view', 'employee_documents.view', 'employeeDocument.view', 'employee-document.view', 'view employee documents']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['employee-documents.create', 'employee_documents.create', 'employeeDocument.create', 'employee-document.create', 'create employee documents']);
    }

    public function update(User $user, EmployeeDocument $record): bool
    {
        return $this->canAny($user, ['employee-documents.update', 'employee_documents.update', 'employeeDocument.update', 'employee-document.update', 'edit employee documents', 'update employee documents']);
    }

    public function delete(User $user, EmployeeDocument $record): bool
    {
        return $this->canAny($user, ['employee-documents.delete', 'employee_documents.delete', 'employeeDocument.delete', 'employee-document.delete', 'delete employee documents']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['employee-documents.delete', 'employee_documents.delete', 'employeeDocument.delete', 'employee-document.delete', 'delete employee documents']);
    }

    public function forceDelete(User $user, EmployeeDocument $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, EmployeeDocument $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, EmployeeDocument $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['employee-documents.update', 'employee_documents.update', 'employeeDocument.update', 'employee-document.update', 'edit employee documents', 'update employee documents']);
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