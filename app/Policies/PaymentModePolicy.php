<?php

namespace App\Policies;

use App\Models\PaymentMode;
use App\Models\User;

class PaymentModePolicy
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
        return $this->canAny($user, ['payment-modes.view', 'payment_modes.view', 'paymentMode.view', 'payment-mode.view', 'view payment modes']);
    }

    public function view(User $user, PaymentMode $record): bool
    {
        return $this->canAny($user, ['payment-modes.view', 'payment_modes.view', 'paymentMode.view', 'payment-mode.view', 'view payment modes']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['payment-modes.create', 'payment_modes.create', 'paymentMode.create', 'payment-mode.create', 'create payment modes']);
    }

    public function update(User $user, PaymentMode $record): bool
    {
        return $this->canAny($user, ['payment-modes.update', 'payment_modes.update', 'paymentMode.update', 'payment-mode.update', 'edit payment modes', 'update payment modes']);
    }

    public function delete(User $user, PaymentMode $record): bool
    {
        return $this->canAny($user, ['payment-modes.delete', 'payment_modes.delete', 'paymentMode.delete', 'payment-mode.delete', 'delete payment modes']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['payment-modes.delete', 'payment_modes.delete', 'paymentMode.delete', 'payment-mode.delete', 'delete payment modes']);
    }

    public function forceDelete(User $user, PaymentMode $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, PaymentMode $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, PaymentMode $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['payment-modes.update', 'payment_modes.update', 'paymentMode.update', 'payment-mode.update', 'edit payment modes', 'update payment modes']);
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