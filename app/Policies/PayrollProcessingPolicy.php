<?php

namespace App\Policies;

use App\Models\PayrollProcessing;
use App\Models\User;

class PayrollProcessingPolicy
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
        return $this->canAny($user, ['payroll-processings.view', 'payroll_processings.view', 'payrollProcessing.view', 'payroll-processing.view', 'view payroll processings']);
    }

    public function view(User $user, PayrollProcessing $record): bool
    {
        return $this->canAny($user, ['payroll-processings.view', 'payroll_processings.view', 'payrollProcessing.view', 'payroll-processing.view', 'view payroll processings']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['payroll-processings.create', 'payroll_processings.create', 'payrollProcessing.create', 'payroll-processing.create', 'create payroll processings']);
    }

    public function update(User $user, PayrollProcessing $record): bool
    {
        return $this->canAny($user, ['payroll-processings.update', 'payroll_processings.update', 'payrollProcessing.update', 'payroll-processing.update', 'edit payroll processings', 'update payroll processings']);
    }

    public function delete(User $user, PayrollProcessing $record): bool
    {
        return $this->canAny($user, ['payroll-processings.delete', 'payroll_processings.delete', 'payrollProcessing.delete', 'payroll-processing.delete', 'delete payroll processings']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['payroll-processings.delete', 'payroll_processings.delete', 'payrollProcessing.delete', 'payroll-processing.delete', 'delete payroll processings']);
    }

    public function forceDelete(User $user, PayrollProcessing $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, PayrollProcessing $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, PayrollProcessing $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['payroll-processings.update', 'payroll_processings.update', 'payrollProcessing.update', 'payroll-processing.update', 'edit payroll processings', 'update payroll processings']);
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