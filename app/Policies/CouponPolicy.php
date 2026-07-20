<?php

namespace App\Policies;

use App\Models\Coupon;
use App\Models\User;

class CouponPolicy
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
        return $this->canAny($user, ['coupons.view', 'coupons.view', 'coupon.view', 'coupon.view', 'view coupons']);
    }

    public function view(User $user, Coupon $record): bool
    {
        return $this->canAny($user, ['coupons.view', 'coupons.view', 'coupon.view', 'coupon.view', 'view coupons']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['coupons.create', 'coupons.create', 'coupon.create', 'coupon.create', 'create coupons']);
    }

    public function update(User $user, Coupon $record): bool
    {
        return $this->canAny($user, ['coupons.update', 'coupons.update', 'coupon.update', 'coupon.update', 'edit coupons', 'update coupons']);
    }

    public function delete(User $user, Coupon $record): bool
    {
        return $this->canAny($user, ['coupons.delete', 'coupons.delete', 'coupon.delete', 'coupon.delete', 'delete coupons']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['coupons.delete', 'coupons.delete', 'coupon.delete', 'coupon.delete', 'delete coupons']);
    }

    public function forceDelete(User $user, Coupon $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, Coupon $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, Coupon $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['coupons.update', 'coupons.update', 'coupon.update', 'coupon.update', 'edit coupons', 'update coupons']);
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