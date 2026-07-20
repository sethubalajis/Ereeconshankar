<?php

namespace App\Policies;

use App\Models\PackingList;
use App\Models\User;

class PackingListPolicy
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
        return $this->canAny($user, ['packing-lists.view', 'packing_lists.view', 'packingList.view', 'packing-list.view', 'view packing lists']);
    }

    public function view(User $user, PackingList $record): bool
    {
        return $this->canAny($user, ['packing-lists.view', 'packing_lists.view', 'packingList.view', 'packing-list.view', 'view packing lists']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['packing-lists.create', 'packing_lists.create', 'packingList.create', 'packing-list.create', 'create packing lists']);
    }

    public function update(User $user, PackingList $record): bool
    {
        return $this->canAny($user, ['packing-lists.update', 'packing_lists.update', 'packingList.update', 'packing-list.update', 'edit packing lists', 'update packing lists']);
    }

    public function delete(User $user, PackingList $record): bool
    {
        return $this->canAny($user, ['packing-lists.delete', 'packing_lists.delete', 'packingList.delete', 'packing-list.delete', 'delete packing lists']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['packing-lists.delete', 'packing_lists.delete', 'packingList.delete', 'packing-list.delete', 'delete packing lists']);
    }

    public function forceDelete(User $user, PackingList $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, PackingList $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, PackingList $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['packing-lists.update', 'packing_lists.update', 'packingList.update', 'packing-list.update', 'edit packing lists', 'update packing lists']);
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