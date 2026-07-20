<?php

namespace App\Policies;

use App\Models\PickingList;
use App\Models\User;

class PickingListPolicy
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
        return $this->canAny($user, ['picking-lists.view', 'picking_lists.view', 'pickingList.view', 'picking-list.view', 'view picking lists']);
    }

    public function view(User $user, PickingList $record): bool
    {
        return $this->canAny($user, ['picking-lists.view', 'picking_lists.view', 'pickingList.view', 'picking-list.view', 'view picking lists']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['picking-lists.create', 'picking_lists.create', 'pickingList.create', 'picking-list.create', 'create picking lists']);
    }

    public function update(User $user, PickingList $record): bool
    {
        return $this->canAny($user, ['picking-lists.update', 'picking_lists.update', 'pickingList.update', 'picking-list.update', 'edit picking lists', 'update picking lists']);
    }

    public function delete(User $user, PickingList $record): bool
    {
        return $this->canAny($user, ['picking-lists.delete', 'picking_lists.delete', 'pickingList.delete', 'picking-list.delete', 'delete picking lists']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['picking-lists.delete', 'picking_lists.delete', 'pickingList.delete', 'picking-list.delete', 'delete picking lists']);
    }

    public function forceDelete(User $user, PickingList $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, PickingList $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, PickingList $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['picking-lists.update', 'picking_lists.update', 'pickingList.update', 'picking-list.update', 'edit picking lists', 'update picking lists']);
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