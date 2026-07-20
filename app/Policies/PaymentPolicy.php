<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;

class PaymentPolicy
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
        return $this->canAny($user, ['payments.view', 'payments.view', 'payment.view', 'payment.view', 'view payments']);
    }

    public function view(User $user, Payment $record): bool
    {
        return $this->canAny($user, ['payments.view', 'payments.view', 'payment.view', 'payment.view', 'view payments']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['payments.create', 'payments.create', 'payment.create', 'payment.create', 'create payments']);
    }

    public function update(User $user, Payment $record): bool
    {
        return $this->canAny($user, ['payments.update', 'payments.update', 'payment.update', 'payment.update', 'edit payments', 'update payments']);
    }

    public function delete(User $user, Payment $record): bool
    {
        return $this->canAny($user, ['payments.delete', 'payments.delete', 'payment.delete', 'payment.delete', 'delete payments']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['payments.delete', 'payments.delete', 'payment.delete', 'payment.delete', 'delete payments']);
    }

    public function forceDelete(User $user, Payment $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, Payment $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, Payment $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['payments.update', 'payments.update', 'payment.update', 'payment.update', 'edit payments', 'update payments']);
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