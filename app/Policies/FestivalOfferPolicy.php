<?php

namespace App\Policies;

use App\Models\FestivalOffer;
use App\Models\User;

class FestivalOfferPolicy
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
        return $this->canAny($user, ['festival-offers.view', 'festival_offers.view', 'festivalOffer.view', 'festival-offer.view', 'view festival offers']);
    }

    public function view(User $user, FestivalOffer $record): bool
    {
        return $this->canAny($user, ['festival-offers.view', 'festival_offers.view', 'festivalOffer.view', 'festival-offer.view', 'view festival offers']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['festival-offers.create', 'festival_offers.create', 'festivalOffer.create', 'festival-offer.create', 'create festival offers']);
    }

    public function update(User $user, FestivalOffer $record): bool
    {
        return $this->canAny($user, ['festival-offers.update', 'festival_offers.update', 'festivalOffer.update', 'festival-offer.update', 'edit festival offers', 'update festival offers']);
    }

    public function delete(User $user, FestivalOffer $record): bool
    {
        return $this->canAny($user, ['festival-offers.delete', 'festival_offers.delete', 'festivalOffer.delete', 'festival-offer.delete', 'delete festival offers']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['festival-offers.delete', 'festival_offers.delete', 'festivalOffer.delete', 'festival-offer.delete', 'delete festival offers']);
    }

    public function forceDelete(User $user, FestivalOffer $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, FestivalOffer $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, FestivalOffer $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['festival-offers.update', 'festival_offers.update', 'festivalOffer.update', 'festival-offer.update', 'edit festival offers', 'update festival offers']);
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