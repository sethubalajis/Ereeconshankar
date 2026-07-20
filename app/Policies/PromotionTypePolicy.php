<?php

namespace App\Policies;

use App\Models\PromotionType;
use App\Models\User;

class PromotionTypePolicy
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
        return $this->canAny($user, ['promotion-types.view', 'promotion_types.view', 'promotionType.view', 'promotion-type.view', 'view promotion types']);
    }

    public function view(User $user, PromotionType $record): bool
    {
        return $this->canAny($user, ['promotion-types.view', 'promotion_types.view', 'promotionType.view', 'promotion-type.view', 'view promotion types']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['promotion-types.create', 'promotion_types.create', 'promotionType.create', 'promotion-type.create', 'create promotion types']);
    }

    public function update(User $user, PromotionType $record): bool
    {
        return $this->canAny($user, ['promotion-types.update', 'promotion_types.update', 'promotionType.update', 'promotion-type.update', 'edit promotion types', 'update promotion types']);
    }

    public function delete(User $user, PromotionType $record): bool
    {
        return $this->canAny($user, ['promotion-types.delete', 'promotion_types.delete', 'promotionType.delete', 'promotion-type.delete', 'delete promotion types']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['promotion-types.delete', 'promotion_types.delete', 'promotionType.delete', 'promotion-type.delete', 'delete promotion types']);
    }

    public function forceDelete(User $user, PromotionType $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, PromotionType $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, PromotionType $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['promotion-types.update', 'promotion_types.update', 'promotionType.update', 'promotion-type.update', 'edit promotion types', 'update promotion types']);
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