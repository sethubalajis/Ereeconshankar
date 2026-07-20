<?php

namespace App\Policies;

use App\Models\ServiceReport;
use App\Models\User;

class ServiceReportPolicy
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
        return $this->canAny($user, ['service-reports.view', 'service_reports.view', 'serviceReport.view', 'service-report.view', 'view service reports']);
    }

    public function view(User $user, ServiceReport $record): bool
    {
        return $this->canAny($user, ['service-reports.view', 'service_reports.view', 'serviceReport.view', 'service-report.view', 'view service reports']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['service-reports.create', 'service_reports.create', 'serviceReport.create', 'service-report.create', 'create service reports']);
    }

    public function update(User $user, ServiceReport $record): bool
    {
        return $this->canAny($user, ['service-reports.update', 'service_reports.update', 'serviceReport.update', 'service-report.update', 'edit service reports', 'update service reports']);
    }

    public function delete(User $user, ServiceReport $record): bool
    {
        return $this->canAny($user, ['service-reports.delete', 'service_reports.delete', 'serviceReport.delete', 'service-report.delete', 'delete service reports']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['service-reports.delete', 'service_reports.delete', 'serviceReport.delete', 'service-report.delete', 'delete service reports']);
    }

    public function forceDelete(User $user, ServiceReport $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, ServiceReport $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, ServiceReport $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['service-reports.update', 'service_reports.update', 'serviceReport.update', 'service-report.update', 'edit service reports', 'update service reports']);
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