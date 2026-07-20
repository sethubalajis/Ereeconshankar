<?php

namespace App\Policies;

use App\Models\PayrollCycle;
use App\Models\User;

class PayrollCyclePolicy
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
        return $this->canAny($user, ['payroll-cycles.view', 'payroll_cycles.view', 'payrollCycle.view', 'payroll-cycle.view', 'view payroll cycles']);
    }

    public function view(User $user, PayrollCycle $record): bool
    {
        return $this->canAny($user, ['payroll-cycles.view', 'payroll_cycles.view', 'payrollCycle.view', 'payroll-cycle.view', 'view payroll cycles']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['payroll-cycles.create', 'payroll_cycles.create', 'payrollCycle.create', 'payroll-cycle.create', 'create payroll cycles']);
    }

    public function update(User $user, PayrollCycle $record): bool
    {
        return $this->canAny($user, ['payroll-cycles.update', 'payroll_cycles.update', 'payrollCycle.update', 'payroll-cycle.update', 'edit payroll cycles', 'update payroll cycles']);
    }

    public function delete(User $user, PayrollCycle $record): bool
    {
        return $this->canAny($user, ['payroll-cycles.delete', 'payroll_cycles.delete', 'payrollCycle.delete', 'payroll-cycle.delete', 'delete payroll cycles']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['payroll-cycles.delete', 'payroll_cycles.delete', 'payrollCycle.delete', 'payroll-cycle.delete', 'delete payroll cycles']);
    }

    public function forceDelete(User $user, PayrollCycle $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, PayrollCycle $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, PayrollCycle $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['payroll-cycles.update', 'payroll_cycles.update', 'payrollCycle.update', 'payroll-cycle.update', 'edit payroll cycles', 'update payroll cycles']);
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