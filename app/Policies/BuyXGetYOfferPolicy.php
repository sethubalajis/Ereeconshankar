<?php

namespace App\Policies;

use App\Models\BuyXGetYOffer;
use App\Models\User;

class BuyXGetYOfferPolicy
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
        return $this->canAny($user, ['buy-x-get-y-offers.view', 'buy_x_get_y_offers.view', 'buyXGetYOffer.view', 'buy-x-get-y-offer.view', 'view buy x get y offers']);
    }

    public function view(User $user, BuyXGetYOffer $record): bool
    {
        return $this->canAny($user, ['buy-x-get-y-offers.view', 'buy_x_get_y_offers.view', 'buyXGetYOffer.view', 'buy-x-get-y-offer.view', 'view buy x get y offers']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['buy-x-get-y-offers.create', 'buy_x_get_y_offers.create', 'buyXGetYOffer.create', 'buy-x-get-y-offer.create', 'create buy x get y offers']);
    }

    public function update(User $user, BuyXGetYOffer $record): bool
    {
        return $this->canAny($user, ['buy-x-get-y-offers.update', 'buy_x_get_y_offers.update', 'buyXGetYOffer.update', 'buy-x-get-y-offer.update', 'edit buy x get y offers', 'update buy x get y offers']);
    }

    public function delete(User $user, BuyXGetYOffer $record): bool
    {
        return $this->canAny($user, ['buy-x-get-y-offers.delete', 'buy_x_get_y_offers.delete', 'buyXGetYOffer.delete', 'buy-x-get-y-offer.delete', 'delete buy x get y offers']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['buy-x-get-y-offers.delete', 'buy_x_get_y_offers.delete', 'buyXGetYOffer.delete', 'buy-x-get-y-offer.delete', 'delete buy x get y offers']);
    }

    public function forceDelete(User $user, BuyXGetYOffer $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, BuyXGetYOffer $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, BuyXGetYOffer $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['buy-x-get-y-offers.update', 'buy_x_get_y_offers.update', 'buyXGetYOffer.update', 'buy-x-get-y-offer.update', 'edit buy x get y offers', 'update buy x get y offers']);
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