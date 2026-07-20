<?php

namespace App\Policies;

use App\Models\LoyaltyCard;
use App\Models\User;

class LoyaltyCardPolicy
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
        return $this->canAny($user, ['loyalty-cards.view', 'loyalty_cards.view', 'loyaltyCard.view', 'loyalty-card.view', 'view loyalty cards']);
    }

    public function view(User $user, LoyaltyCard $record): bool
    {
        return $this->canAny($user, ['loyalty-cards.view', 'loyalty_cards.view', 'loyaltyCard.view', 'loyalty-card.view', 'view loyalty cards']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['loyalty-cards.create', 'loyalty_cards.create', 'loyaltyCard.create', 'loyalty-card.create', 'create loyalty cards']);
    }

    public function update(User $user, LoyaltyCard $record): bool
    {
        return $this->canAny($user, ['loyalty-cards.update', 'loyalty_cards.update', 'loyaltyCard.update', 'loyalty-card.update', 'edit loyalty cards', 'update loyalty cards']);
    }

    public function delete(User $user, LoyaltyCard $record): bool
    {
        return $this->canAny($user, ['loyalty-cards.delete', 'loyalty_cards.delete', 'loyaltyCard.delete', 'loyalty-card.delete', 'delete loyalty cards']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['loyalty-cards.delete', 'loyalty_cards.delete', 'loyaltyCard.delete', 'loyalty-card.delete', 'delete loyalty cards']);
    }

    public function forceDelete(User $user, LoyaltyCard $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, LoyaltyCard $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, LoyaltyCard $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['loyalty-cards.update', 'loyalty_cards.update', 'loyaltyCard.update', 'loyalty-card.update', 'edit loyalty cards', 'update loyalty cards']);
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