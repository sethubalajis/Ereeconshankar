<?php

namespace App\Policies;

use App\Models\Stock;
use App\Models\User;

class StockPolicy
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
        return $this->canAny($user, ['stocks.view', 'stocks.view', 'stock.view', 'stock.view', 'view stocks']);
    }

    public function view(User $user, Stock $record): bool
    {
        return $this->canAny($user, ['stocks.view', 'stocks.view', 'stock.view', 'stock.view', 'view stocks']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['stocks.create', 'stocks.create', 'stock.create', 'stock.create', 'create stocks']);
    }

    public function update(User $user, Stock $record): bool
    {
        return $this->canAny($user, ['stocks.update', 'stocks.update', 'stock.update', 'stock.update', 'edit stocks', 'update stocks']);
    }

    public function delete(User $user, Stock $record): bool
    {
        return $this->canAny($user, ['stocks.delete', 'stocks.delete', 'stock.delete', 'stock.delete', 'delete stocks']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['stocks.delete', 'stocks.delete', 'stock.delete', 'stock.delete', 'delete stocks']);
    }

    public function forceDelete(User $user, Stock $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, Stock $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, Stock $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['stocks.update', 'stocks.update', 'stock.update', 'stock.update', 'edit stocks', 'update stocks']);
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