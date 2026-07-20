<?php

namespace App\Policies;

use App\Models\ComboOffer;
use App\Models\User;

class ComboOfferPolicy
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
        return $this->canAny($user, ['combo-offers.view', 'combo_offers.view', 'comboOffer.view', 'combo-offer.view', 'view combo offers']);
    }

    public function view(User $user, ComboOffer $record): bool
    {
        return $this->canAny($user, ['combo-offers.view', 'combo_offers.view', 'comboOffer.view', 'combo-offer.view', 'view combo offers']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['combo-offers.create', 'combo_offers.create', 'comboOffer.create', 'combo-offer.create', 'create combo offers']);
    }

    public function update(User $user, ComboOffer $record): bool
    {
        return $this->canAny($user, ['combo-offers.update', 'combo_offers.update', 'comboOffer.update', 'combo-offer.update', 'edit combo offers', 'update combo offers']);
    }

    public function delete(User $user, ComboOffer $record): bool
    {
        return $this->canAny($user, ['combo-offers.delete', 'combo_offers.delete', 'comboOffer.delete', 'combo-offer.delete', 'delete combo offers']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['combo-offers.delete', 'combo_offers.delete', 'comboOffer.delete', 'combo-offer.delete', 'delete combo offers']);
    }

    public function forceDelete(User $user, ComboOffer $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, ComboOffer $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, ComboOffer $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['combo-offers.update', 'combo_offers.update', 'comboOffer.update', 'combo-offer.update', 'edit combo offers', 'update combo offers']);
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