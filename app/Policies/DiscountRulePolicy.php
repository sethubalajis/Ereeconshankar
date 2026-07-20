<?php

namespace App\Policies;

use App\Models\DiscountRule;
use App\Models\User;

class DiscountRulePolicy
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
        return $this->canAny($user, ['discount-rules.view', 'discount_rules.view', 'discountRule.view', 'discount-rule.view', 'view discount rules']);
    }

    public function view(User $user, DiscountRule $record): bool
    {
        return $this->canAny($user, ['discount-rules.view', 'discount_rules.view', 'discountRule.view', 'discount-rule.view', 'view discount rules']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['discount-rules.create', 'discount_rules.create', 'discountRule.create', 'discount-rule.create', 'create discount rules']);
    }

    public function update(User $user, DiscountRule $record): bool
    {
        return $this->canAny($user, ['discount-rules.update', 'discount_rules.update', 'discountRule.update', 'discount-rule.update', 'edit discount rules', 'update discount rules']);
    }

    public function delete(User $user, DiscountRule $record): bool
    {
        return $this->canAny($user, ['discount-rules.delete', 'discount_rules.delete', 'discountRule.delete', 'discount-rule.delete', 'delete discount rules']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['discount-rules.delete', 'discount_rules.delete', 'discountRule.delete', 'discount-rule.delete', 'delete discount rules']);
    }

    public function forceDelete(User $user, DiscountRule $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, DiscountRule $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, DiscountRule $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['discount-rules.update', 'discount_rules.update', 'discountRule.update', 'discount-rule.update', 'edit discount rules', 'update discount rules']);
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