<?php

namespace App\Policies;

use App\Models\KpiReport;
use App\Models\User;

class KpiReportPolicy
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
        return $this->canAny($user, ['kpi-reports.view', 'kpi_reports.view', 'kpiReport.view', 'kpi-report.view', 'view kpi reports']);
    }

    public function view(User $user, KpiReport $record): bool
    {
        return $this->canAny($user, ['kpi-reports.view', 'kpi_reports.view', 'kpiReport.view', 'kpi-report.view', 'view kpi reports']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['kpi-reports.create', 'kpi_reports.create', 'kpiReport.create', 'kpi-report.create', 'create kpi reports']);
    }

    public function update(User $user, KpiReport $record): bool
    {
        return $this->canAny($user, ['kpi-reports.update', 'kpi_reports.update', 'kpiReport.update', 'kpi-report.update', 'edit kpi reports', 'update kpi reports']);
    }

    public function delete(User $user, KpiReport $record): bool
    {
        return $this->canAny($user, ['kpi-reports.delete', 'kpi_reports.delete', 'kpiReport.delete', 'kpi-report.delete', 'delete kpi reports']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['kpi-reports.delete', 'kpi_reports.delete', 'kpiReport.delete', 'kpi-report.delete', 'delete kpi reports']);
    }

    public function forceDelete(User $user, KpiReport $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, KpiReport $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, KpiReport $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['kpi-reports.update', 'kpi_reports.update', 'kpiReport.update', 'kpi-report.update', 'edit kpi reports', 'update kpi reports']);
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