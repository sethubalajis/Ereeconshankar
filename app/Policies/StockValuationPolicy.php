<?php

namespace App\Policies;

use App\Models\StockValuation;
use App\Models\User;

class StockValuationPolicy
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
        return $this->canAny($user, ['stock-valuations.view', 'stock_valuations.view', 'stockValuation.view', 'stock-valuation.view', 'view stock valuations']);
    }

    public function view(User $user, StockValuation $record): bool
    {
        return $this->canAny($user, ['stock-valuations.view', 'stock_valuations.view', 'stockValuation.view', 'stock-valuation.view', 'view stock valuations']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['stock-valuations.create', 'stock_valuations.create', 'stockValuation.create', 'stock-valuation.create', 'create stock valuations']);
    }

    public function update(User $user, StockValuation $record): bool
    {
        return $this->canAny($user, ['stock-valuations.update', 'stock_valuations.update', 'stockValuation.update', 'stock-valuation.update', 'edit stock valuations', 'update stock valuations']);
    }

    public function delete(User $user, StockValuation $record): bool
    {
        return $this->canAny($user, ['stock-valuations.delete', 'stock_valuations.delete', 'stockValuation.delete', 'stock-valuation.delete', 'delete stock valuations']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['stock-valuations.delete', 'stock_valuations.delete', 'stockValuation.delete', 'stock-valuation.delete', 'delete stock valuations']);
    }

    public function forceDelete(User $user, StockValuation $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, StockValuation $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, StockValuation $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['stock-valuations.update', 'stock_valuations.update', 'stockValuation.update', 'stock-valuation.update', 'edit stock valuations', 'update stock valuations']);
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