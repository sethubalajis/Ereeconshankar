<?php

namespace App\Policies;

use App\Models\GstReport;
use App\Models\User;

class GstReportPolicy
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
        return $this->canAny($user, ['gst-reports.view', 'gst_reports.view', 'gstReport.view', 'gst-report.view', 'view gst reports']);
    }

    public function view(User $user, GstReport $record): bool
    {
        return $this->canAny($user, ['gst-reports.view', 'gst_reports.view', 'gstReport.view', 'gst-report.view', 'view gst reports']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['gst-reports.create', 'gst_reports.create', 'gstReport.create', 'gst-report.create', 'create gst reports']);
    }

    public function update(User $user, GstReport $record): bool
    {
        return $this->canAny($user, ['gst-reports.update', 'gst_reports.update', 'gstReport.update', 'gst-report.update', 'edit gst reports', 'update gst reports']);
    }

    public function delete(User $user, GstReport $record): bool
    {
        return $this->canAny($user, ['gst-reports.delete', 'gst_reports.delete', 'gstReport.delete', 'gst-report.delete', 'delete gst reports']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['gst-reports.delete', 'gst_reports.delete', 'gstReport.delete', 'gst-report.delete', 'delete gst reports']);
    }

    public function forceDelete(User $user, GstReport $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, GstReport $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, GstReport $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['gst-reports.update', 'gst_reports.update', 'gstReport.update', 'gst-report.update', 'edit gst reports', 'update gst reports']);
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