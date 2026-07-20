<?php

namespace App\Policies;

use App\Models\PromoCode;
use App\Models\User;

class PromoCodePolicy
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
        return $this->canAny($user, ['promo-codes.view', 'promo_codes.view', 'promoCode.view', 'promo-code.view', 'view promo codes']);
    }

    public function view(User $user, PromoCode $record): bool
    {
        return $this->canAny($user, ['promo-codes.view', 'promo_codes.view', 'promoCode.view', 'promo-code.view', 'view promo codes']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['promo-codes.create', 'promo_codes.create', 'promoCode.create', 'promo-code.create', 'create promo codes']);
    }

    public function update(User $user, PromoCode $record): bool
    {
        return $this->canAny($user, ['promo-codes.update', 'promo_codes.update', 'promoCode.update', 'promo-code.update', 'edit promo codes', 'update promo codes']);
    }

    public function delete(User $user, PromoCode $record): bool
    {
        return $this->canAny($user, ['promo-codes.delete', 'promo_codes.delete', 'promoCode.delete', 'promo-code.delete', 'delete promo codes']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['promo-codes.delete', 'promo_codes.delete', 'promoCode.delete', 'promo-code.delete', 'delete promo codes']);
    }

    public function forceDelete(User $user, PromoCode $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, PromoCode $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, PromoCode $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['promo-codes.update', 'promo_codes.update', 'promoCode.update', 'promo-code.update', 'edit promo codes', 'update promo codes']);
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