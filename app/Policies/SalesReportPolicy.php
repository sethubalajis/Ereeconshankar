<?php

namespace App\Policies;

use App\Models\SalesReport;
use App\Models\User;

class SalesReportPolicy
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
        return $this->canAny($user, ['sales-reports.view', 'sales_reports.view', 'salesReport.view', 'sales-report.view', 'view sales reports']);
    }

    public function view(User $user, SalesReport $record): bool
    {
        return $this->canAny($user, ['sales-reports.view', 'sales_reports.view', 'salesReport.view', 'sales-report.view', 'view sales reports']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['sales-reports.create', 'sales_reports.create', 'salesReport.create', 'sales-report.create', 'create sales reports']);
    }

    public function update(User $user, SalesReport $record): bool
    {
        return $this->canAny($user, ['sales-reports.update', 'sales_reports.update', 'salesReport.update', 'sales-report.update', 'edit sales reports', 'update sales reports']);
    }

    public function delete(User $user, SalesReport $record): bool
    {
        return $this->canAny($user, ['sales-reports.delete', 'sales_reports.delete', 'salesReport.delete', 'sales-report.delete', 'delete sales reports']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['sales-reports.delete', 'sales_reports.delete', 'salesReport.delete', 'sales-report.delete', 'delete sales reports']);
    }

    public function forceDelete(User $user, SalesReport $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, SalesReport $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, SalesReport $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['sales-reports.update', 'sales_reports.update', 'salesReport.update', 'sales-report.update', 'edit sales reports', 'update sales reports']);
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