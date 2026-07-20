<?php

namespace App\Policies;

use App\Models\LoyaltyPoint;
use App\Models\User;

class LoyaltyPointPolicy
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
        return $this->canAny($user, ['loyalty-points.view', 'loyalty_points.view', 'loyaltyPoint.view', 'loyalty-point.view', 'view loyalty points']);
    }

    public function view(User $user, LoyaltyPoint $record): bool
    {
        return $this->canAny($user, ['loyalty-points.view', 'loyalty_points.view', 'loyaltyPoint.view', 'loyalty-point.view', 'view loyalty points']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['loyalty-points.create', 'loyalty_points.create', 'loyaltyPoint.create', 'loyalty-point.create', 'create loyalty points']);
    }

    public function update(User $user, LoyaltyPoint $record): bool
    {
        return $this->canAny($user, ['loyalty-points.update', 'loyalty_points.update', 'loyaltyPoint.update', 'loyalty-point.update', 'edit loyalty points', 'update loyalty points']);
    }

    public function delete(User $user, LoyaltyPoint $record): bool
    {
        return $this->canAny($user, ['loyalty-points.delete', 'loyalty_points.delete', 'loyaltyPoint.delete', 'loyalty-point.delete', 'delete loyalty points']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['loyalty-points.delete', 'loyalty_points.delete', 'loyaltyPoint.delete', 'loyalty-point.delete', 'delete loyalty points']);
    }

    public function forceDelete(User $user, LoyaltyPoint $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, LoyaltyPoint $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, LoyaltyPoint $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['loyalty-points.update', 'loyalty_points.update', 'loyaltyPoint.update', 'loyalty-point.update', 'edit loyalty points', 'update loyalty points']);
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