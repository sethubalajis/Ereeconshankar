<?php

namespace App\Policies;

use App\Models\StockAdjustment;
use App\Models\User;

class StockAdjustmentPolicy
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
        return $this->canAny($user, ['stock-adjustments.view', 'stock_adjustments.view', 'stockAdjustment.view', 'stock-adjustment.view', 'view stock adjustments']);
    }

    public function view(User $user, StockAdjustment $record): bool
    {
        return $this->canAny($user, ['stock-adjustments.view', 'stock_adjustments.view', 'stockAdjustment.view', 'stock-adjustment.view', 'view stock adjustments']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['stock-adjustments.create', 'stock_adjustments.create', 'stockAdjustment.create', 'stock-adjustment.create', 'create stock adjustments']);
    }

    public function update(User $user, StockAdjustment $record): bool
    {
        return $this->canAny($user, ['stock-adjustments.update', 'stock_adjustments.update', 'stockAdjustment.update', 'stock-adjustment.update', 'edit stock adjustments', 'update stock adjustments']);
    }

    public function delete(User $user, StockAdjustment $record): bool
    {
        return $this->canAny($user, ['stock-adjustments.delete', 'stock_adjustments.delete', 'stockAdjustment.delete', 'stock-adjustment.delete', 'delete stock adjustments']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['stock-adjustments.delete', 'stock_adjustments.delete', 'stockAdjustment.delete', 'stock-adjustment.delete', 'delete stock adjustments']);
    }

    public function forceDelete(User $user, StockAdjustment $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, StockAdjustment $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, StockAdjustment $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['stock-adjustments.update', 'stock_adjustments.update', 'stockAdjustment.update', 'stock-adjustment.update', 'edit stock adjustments', 'update stock adjustments']);
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