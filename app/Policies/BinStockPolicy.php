<?php

namespace App\Policies;

use App\Models\BinStock;
use App\Models\User;

class BinStockPolicy
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
        return $this->canAny($user, ['bin-stocks.view', 'bin_stocks.view', 'binStock.view', 'bin-stock.view', 'view bin stocks']);
    }

    public function view(User $user, BinStock $record): bool
    {
        return $this->canAny($user, ['bin-stocks.view', 'bin_stocks.view', 'binStock.view', 'bin-stock.view', 'view bin stocks']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['bin-stocks.create', 'bin_stocks.create', 'binStock.create', 'bin-stock.create', 'create bin stocks']);
    }

    public function update(User $user, BinStock $record): bool
    {
        return $this->canAny($user, ['bin-stocks.update', 'bin_stocks.update', 'binStock.update', 'bin-stock.update', 'edit bin stocks', 'update bin stocks']);
    }

    public function delete(User $user, BinStock $record): bool
    {
        return $this->canAny($user, ['bin-stocks.delete', 'bin_stocks.delete', 'binStock.delete', 'bin-stock.delete', 'delete bin stocks']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['bin-stocks.delete', 'bin_stocks.delete', 'binStock.delete', 'bin-stock.delete', 'delete bin stocks']);
    }

    public function forceDelete(User $user, BinStock $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, BinStock $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, BinStock $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['bin-stocks.update', 'bin_stocks.update', 'binStock.update', 'bin-stock.update', 'edit bin stocks', 'update bin stocks']);
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