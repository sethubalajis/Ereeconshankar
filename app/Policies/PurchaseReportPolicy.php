<?php

namespace App\Policies;

use App\Models\PurchaseReport;
use App\Models\User;

class PurchaseReportPolicy
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
        return $this->canAny($user, ['purchase-reports.view', 'purchase_reports.view', 'purchaseReport.view', 'purchase-report.view', 'view purchase reports']);
    }

    public function view(User $user, PurchaseReport $record): bool
    {
        return $this->canAny($user, ['purchase-reports.view', 'purchase_reports.view', 'purchaseReport.view', 'purchase-report.view', 'view purchase reports']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['purchase-reports.create', 'purchase_reports.create', 'purchaseReport.create', 'purchase-report.create', 'create purchase reports']);
    }

    public function update(User $user, PurchaseReport $record): bool
    {
        return $this->canAny($user, ['purchase-reports.update', 'purchase_reports.update', 'purchaseReport.update', 'purchase-report.update', 'edit purchase reports', 'update purchase reports']);
    }

    public function delete(User $user, PurchaseReport $record): bool
    {
        return $this->canAny($user, ['purchase-reports.delete', 'purchase_reports.delete', 'purchaseReport.delete', 'purchase-report.delete', 'delete purchase reports']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['purchase-reports.delete', 'purchase_reports.delete', 'purchaseReport.delete', 'purchase-report.delete', 'delete purchase reports']);
    }

    public function forceDelete(User $user, PurchaseReport $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, PurchaseReport $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, PurchaseReport $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['purchase-reports.update', 'purchase_reports.update', 'purchaseReport.update', 'purchase-report.update', 'edit purchase reports', 'update purchase reports']);
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