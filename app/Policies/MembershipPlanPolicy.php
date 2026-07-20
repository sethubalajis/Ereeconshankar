<?php

namespace App\Policies;

use App\Models\MembershipPlan;
use App\Models\User;

class MembershipPlanPolicy
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
        return $this->canAny($user, ['membership-plans.view', 'membership_plans.view', 'membershipPlan.view', 'membership-plan.view', 'view membership plans']);
    }

    public function view(User $user, MembershipPlan $record): bool
    {
        return $this->canAny($user, ['membership-plans.view', 'membership_plans.view', 'membershipPlan.view', 'membership-plan.view', 'view membership plans']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['membership-plans.create', 'membership_plans.create', 'membershipPlan.create', 'membership-plan.create', 'create membership plans']);
    }

    public function update(User $user, MembershipPlan $record): bool
    {
        return $this->canAny($user, ['membership-plans.update', 'membership_plans.update', 'membershipPlan.update', 'membership-plan.update', 'edit membership plans', 'update membership plans']);
    }

    public function delete(User $user, MembershipPlan $record): bool
    {
        return $this->canAny($user, ['membership-plans.delete', 'membership_plans.delete', 'membershipPlan.delete', 'membership-plan.delete', 'delete membership plans']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['membership-plans.delete', 'membership_plans.delete', 'membershipPlan.delete', 'membership-plan.delete', 'delete membership plans']);
    }

    public function forceDelete(User $user, MembershipPlan $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, MembershipPlan $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, MembershipPlan $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['membership-plans.update', 'membership_plans.update', 'membershipPlan.update', 'membership-plan.update', 'edit membership plans', 'update membership plans']);
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