<?php

namespace App\Policies;

use App\Models\InventoryReport;
use App\Models\User;

class InventoryReportPolicy
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
        return $this->canAny($user, ['inventory-reports.view', 'inventory_reports.view', 'inventoryReport.view', 'inventory-report.view', 'view inventory reports']);
    }

    public function view(User $user, InventoryReport $record): bool
    {
        return $this->canAny($user, ['inventory-reports.view', 'inventory_reports.view', 'inventoryReport.view', 'inventory-report.view', 'view inventory reports']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['inventory-reports.create', 'inventory_reports.create', 'inventoryReport.create', 'inventory-report.create', 'create inventory reports']);
    }

    public function update(User $user, InventoryReport $record): bool
    {
        return $this->canAny($user, ['inventory-reports.update', 'inventory_reports.update', 'inventoryReport.update', 'inventory-report.update', 'edit inventory reports', 'update inventory reports']);
    }

    public function delete(User $user, InventoryReport $record): bool
    {
        return $this->canAny($user, ['inventory-reports.delete', 'inventory_reports.delete', 'inventoryReport.delete', 'inventory-report.delete', 'delete inventory reports']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['inventory-reports.delete', 'inventory_reports.delete', 'inventoryReport.delete', 'inventory-report.delete', 'delete inventory reports']);
    }

    public function forceDelete(User $user, InventoryReport $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, InventoryReport $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, InventoryReport $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['inventory-reports.update', 'inventory_reports.update', 'inventoryReport.update', 'inventory-report.update', 'edit inventory reports', 'update inventory reports']);
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