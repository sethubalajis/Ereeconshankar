<?php

namespace App\Policies;

use App\Models\FinancialReport;
use App\Models\User;

class FinancialReportPolicy
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
        return $this->canAny($user, ['financial-reports.view', 'financial_reports.view', 'financialReport.view', 'financial-report.view', 'view financial reports']);
    }

    public function view(User $user, FinancialReport $record): bool
    {
        return $this->canAny($user, ['financial-reports.view', 'financial_reports.view', 'financialReport.view', 'financial-report.view', 'view financial reports']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['financial-reports.create', 'financial_reports.create', 'financialReport.create', 'financial-report.create', 'create financial reports']);
    }

    public function update(User $user, FinancialReport $record): bool
    {
        return $this->canAny($user, ['financial-reports.update', 'financial_reports.update', 'financialReport.update', 'financial-report.update', 'edit financial reports', 'update financial reports']);
    }

    public function delete(User $user, FinancialReport $record): bool
    {
        return $this->canAny($user, ['financial-reports.delete', 'financial_reports.delete', 'financialReport.delete', 'financial-report.delete', 'delete financial reports']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['financial-reports.delete', 'financial_reports.delete', 'financialReport.delete', 'financial-report.delete', 'delete financial reports']);
    }

    public function forceDelete(User $user, FinancialReport $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, FinancialReport $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, FinancialReport $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['financial-reports.update', 'financial_reports.update', 'financialReport.update', 'financial-report.update', 'edit financial reports', 'update financial reports']);
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