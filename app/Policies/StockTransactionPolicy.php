<?php

namespace App\Policies;

use App\Models\StockTransaction;
use App\Models\User;

class StockTransactionPolicy
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
        return $this->canAny($user, ['stock-transactions.view', 'stock_transactions.view', 'stockTransaction.view', 'stock-transaction.view', 'view stock transactions']);
    }

    public function view(User $user, StockTransaction $record): bool
    {
        return $this->canAny($user, ['stock-transactions.view', 'stock_transactions.view', 'stockTransaction.view', 'stock-transaction.view', 'view stock transactions']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['stock-transactions.create', 'stock_transactions.create', 'stockTransaction.create', 'stock-transaction.create', 'create stock transactions']);
    }

    public function update(User $user, StockTransaction $record): bool
    {
        return $this->canAny($user, ['stock-transactions.update', 'stock_transactions.update', 'stockTransaction.update', 'stock-transaction.update', 'edit stock transactions', 'update stock transactions']);
    }

    public function delete(User $user, StockTransaction $record): bool
    {
        return $this->canAny($user, ['stock-transactions.delete', 'stock_transactions.delete', 'stockTransaction.delete', 'stock-transaction.delete', 'delete stock transactions']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['stock-transactions.delete', 'stock_transactions.delete', 'stockTransaction.delete', 'stock-transaction.delete', 'delete stock transactions']);
    }

    public function forceDelete(User $user, StockTransaction $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, StockTransaction $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, StockTransaction $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['stock-transactions.update', 'stock_transactions.update', 'stockTransaction.update', 'stock-transaction.update', 'edit stock transactions', 'update stock transactions']);
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