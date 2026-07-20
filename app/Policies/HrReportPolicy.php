<?php

namespace App\Policies;

use App\Models\HrReport;
use App\Models\User;

class HrReportPolicy
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
        return $this->canAny($user, ['hr-reports.view', 'hr_reports.view', 'hrReport.view', 'hr-report.view', 'view hr reports']);
    }

    public function view(User $user, HrReport $record): bool
    {
        return $this->canAny($user, ['hr-reports.view', 'hr_reports.view', 'hrReport.view', 'hr-report.view', 'view hr reports']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['hr-reports.create', 'hr_reports.create', 'hrReport.create', 'hr-report.create', 'create hr reports']);
    }

    public function update(User $user, HrReport $record): bool
    {
        return $this->canAny($user, ['hr-reports.update', 'hr_reports.update', 'hrReport.update', 'hr-report.update', 'edit hr reports', 'update hr reports']);
    }

    public function delete(User $user, HrReport $record): bool
    {
        return $this->canAny($user, ['hr-reports.delete', 'hr_reports.delete', 'hrReport.delete', 'hr-report.delete', 'delete hr reports']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['hr-reports.delete', 'hr_reports.delete', 'hrReport.delete', 'hr-report.delete', 'delete hr reports']);
    }

    public function forceDelete(User $user, HrReport $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, HrReport $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, HrReport $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['hr-reports.update', 'hr_reports.update', 'hrReport.update', 'hr-report.update', 'edit hr reports', 'update hr reports']);
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