<?php

namespace App\Policies;

use App\Models\GiftVoucher;
use App\Models\User;

class GiftVoucherPolicy
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
        return $this->canAny($user, ['gift-vouchers.view', 'gift_vouchers.view', 'giftVoucher.view', 'gift-voucher.view', 'view gift vouchers']);
    }

    public function view(User $user, GiftVoucher $record): bool
    {
        return $this->canAny($user, ['gift-vouchers.view', 'gift_vouchers.view', 'giftVoucher.view', 'gift-voucher.view', 'view gift vouchers']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['gift-vouchers.create', 'gift_vouchers.create', 'giftVoucher.create', 'gift-voucher.create', 'create gift vouchers']);
    }

    public function update(User $user, GiftVoucher $record): bool
    {
        return $this->canAny($user, ['gift-vouchers.update', 'gift_vouchers.update', 'giftVoucher.update', 'gift-voucher.update', 'edit gift vouchers', 'update gift vouchers']);
    }

    public function delete(User $user, GiftVoucher $record): bool
    {
        return $this->canAny($user, ['gift-vouchers.delete', 'gift_vouchers.delete', 'giftVoucher.delete', 'gift-voucher.delete', 'delete gift vouchers']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['gift-vouchers.delete', 'gift_vouchers.delete', 'giftVoucher.delete', 'gift-voucher.delete', 'delete gift vouchers']);
    }

    public function forceDelete(User $user, GiftVoucher $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, GiftVoucher $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, GiftVoucher $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['gift-vouchers.update', 'gift_vouchers.update', 'giftVoucher.update', 'gift-voucher.update', 'edit gift vouchers', 'update gift vouchers']);
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