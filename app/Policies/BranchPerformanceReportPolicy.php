<?php

namespace App\Policies;

use App\Models\BranchPerformanceReport;
use App\Models\User;

class BranchPerformanceReportPolicy
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
        return $this->canAny($user, ['branch-performance-reports.view', 'branch_performance_reports.view', 'branchPerformanceReport.view', 'branch-performance-report.view', 'view branch performance reports']);
    }

    public function view(User $user, BranchPerformanceReport $record): bool
    {
        return $this->canAny($user, ['branch-performance-reports.view', 'branch_performance_reports.view', 'branchPerformanceReport.view', 'branch-performance-report.view', 'view branch performance reports']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['branch-performance-reports.create', 'branch_performance_reports.create', 'branchPerformanceReport.create', 'branch-performance-report.create', 'create branch performance reports']);
    }

    public function update(User $user, BranchPerformanceReport $record): bool
    {
        return $this->canAny($user, ['branch-performance-reports.update', 'branch_performance_reports.update', 'branchPerformanceReport.update', 'branch-performance-report.update', 'edit branch performance reports', 'update branch performance reports']);
    }

    public function delete(User $user, BranchPerformanceReport $record): bool
    {
        return $this->canAny($user, ['branch-performance-reports.delete', 'branch_performance_reports.delete', 'branchPerformanceReport.delete', 'branch-performance-report.delete', 'delete branch performance reports']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['branch-performance-reports.delete', 'branch_performance_reports.delete', 'branchPerformanceReport.delete', 'branch-performance-report.delete', 'delete branch performance reports']);
    }

    public function forceDelete(User $user, BranchPerformanceReport $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, BranchPerformanceReport $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, BranchPerformanceReport $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['branch-performance-reports.update', 'branch_performance_reports.update', 'branchPerformanceReport.update', 'branch-performance-report.update', 'edit branch performance reports', 'update branch performance reports']);
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