<?php

namespace App\Policies;

use App\Models\StockReservation;
use App\Models\User;

class StockReservationPolicy
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
        return $this->canAny($user, ['stock-reservations.view', 'stock_reservations.view', 'stockReservation.view', 'stock-reservation.view', 'view stock reservations']);
    }

    public function view(User $user, StockReservation $record): bool
    {
        return $this->canAny($user, ['stock-reservations.view', 'stock_reservations.view', 'stockReservation.view', 'stock-reservation.view', 'view stock reservations']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['stock-reservations.create', 'stock_reservations.create', 'stockReservation.create', 'stock-reservation.create', 'create stock reservations']);
    }

    public function update(User $user, StockReservation $record): bool
    {
        return $this->canAny($user, ['stock-reservations.update', 'stock_reservations.update', 'stockReservation.update', 'stock-reservation.update', 'edit stock reservations', 'update stock reservations']);
    }

    public function delete(User $user, StockReservation $record): bool
    {
        return $this->canAny($user, ['stock-reservations.delete', 'stock_reservations.delete', 'stockReservation.delete', 'stock-reservation.delete', 'delete stock reservations']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['stock-reservations.delete', 'stock_reservations.delete', 'stockReservation.delete', 'stock-reservation.delete', 'delete stock reservations']);
    }

    public function forceDelete(User $user, StockReservation $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, StockReservation $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, StockReservation $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['stock-reservations.update', 'stock_reservations.update', 'stockReservation.update', 'stock-reservation.update', 'edit stock reservations', 'update stock reservations']);
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