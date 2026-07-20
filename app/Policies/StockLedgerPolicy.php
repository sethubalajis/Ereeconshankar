<?php

namespace App\Policies;

use App\Models\StockLedger;
use App\Models\User;

class StockLedgerPolicy
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
        return $this->canAny($user, ['stock-ledgers.view', 'stock_ledgers.view', 'stockLedger.view', 'stock-ledger.view', 'view stock ledgers']);
    }

    public function view(User $user, StockLedger $record): bool
    {
        return $this->canAny($user, ['stock-ledgers.view', 'stock_ledgers.view', 'stockLedger.view', 'stock-ledger.view', 'view stock ledgers']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['stock-ledgers.create', 'stock_ledgers.create', 'stockLedger.create', 'stock-ledger.create', 'create stock ledgers']);
    }

    public function update(User $user, StockLedger $record): bool
    {
        return $this->canAny($user, ['stock-ledgers.update', 'stock_ledgers.update', 'stockLedger.update', 'stock-ledger.update', 'edit stock ledgers', 'update stock ledgers']);
    }

    public function delete(User $user, StockLedger $record): bool
    {
        return $this->canAny($user, ['stock-ledgers.delete', 'stock_ledgers.delete', 'stockLedger.delete', 'stock-ledger.delete', 'delete stock ledgers']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['stock-ledgers.delete', 'stock_ledgers.delete', 'stockLedger.delete', 'stock-ledger.delete', 'delete stock ledgers']);
    }

    public function forceDelete(User $user, StockLedger $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, StockLedger $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, StockLedger $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['stock-ledgers.update', 'stock_ledgers.update', 'stockLedger.update', 'stock-ledger.update', 'edit stock ledgers', 'update stock ledgers']);
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