<?php

namespace App\Policies;

use App\Models\FinancialYear;
use App\Models\User;

class FinancialYearPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if ($user->hasRole('Super Admin') || $user->hasRole('Admin')) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $this->canAny($user, ['financial-years.view', 'financial_years.view', 'financialYear.view', 'financial-year.view', 'view financial years']);
    }

    public function view(User $user, FinancialYear $financialYear): bool
    {
        return $this->canAny($user, ['financial-years.view', 'financial_years.view', 'financialYear.view', 'financial-year.view', 'view financial years']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['financial-years.create', 'financial_years.create', 'financialYear.create', 'financial-year.create', 'create financial years']);
    }

    public function update(User $user, FinancialYear $financialYear): bool
    {
        return $this->canAny($user, ['financial-years.update', 'financial_years.update', 'financialYear.update', 'financial-year.update', 'edit financial years', 'update financial years']);
    }

    public function delete(User $user, FinancialYear $financialYear): bool
    {
        return $this->canAny($user, ['financial-years.delete', 'financial_years.delete', 'financialYear.delete', 'financial-year.delete', 'delete financial years']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['financial-years.delete', 'financial_years.delete', 'financialYear.delete', 'financial-year.delete', 'delete financial years']);
    }

    public function forceDelete(User $user, FinancialYear $financialYear): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, FinancialYear $financialYear): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, FinancialYear $financialYear): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['financial-years.update', 'financial_years.update', 'financialYear.update', 'financial-year.update', 'edit financial years', 'update financial years']);
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
