<?php

namespace App\Policies;

use App\Models\EmployeeTransfer;
use App\Models\User;

class EmployeeTransferPolicy
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
        return $this->canAny($user, ['employee-transfers.view', 'employee_transfers.view', 'employeeTransfer.view', 'employee-transfer.view', 'view employee transfers']);
    }

    public function view(User $user, EmployeeTransfer $record): bool
    {
        return $this->canAny($user, ['employee-transfers.view', 'employee_transfers.view', 'employeeTransfer.view', 'employee-transfer.view', 'view employee transfers']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['employee-transfers.create', 'employee_transfers.create', 'employeeTransfer.create', 'employee-transfer.create', 'create employee transfers']);
    }

    public function update(User $user, EmployeeTransfer $record): bool
    {
        return $this->canAny($user, ['employee-transfers.update', 'employee_transfers.update', 'employeeTransfer.update', 'employee-transfer.update', 'edit employee transfers', 'update employee transfers']);
    }

    public function delete(User $user, EmployeeTransfer $record): bool
    {
        return $this->canAny($user, ['employee-transfers.delete', 'employee_transfers.delete', 'employeeTransfer.delete', 'employee-transfer.delete', 'delete employee transfers']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['employee-transfers.delete', 'employee_transfers.delete', 'employeeTransfer.delete', 'employee-transfer.delete', 'delete employee transfers']);
    }

    public function forceDelete(User $user, EmployeeTransfer $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, EmployeeTransfer $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, EmployeeTransfer $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['employee-transfers.update', 'employee_transfers.update', 'employeeTransfer.update', 'employee-transfer.update', 'edit employee transfers', 'update employee transfers']);
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