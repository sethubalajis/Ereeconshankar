<?php

namespace App\Policies;

use App\Models\Collection;
use App\Models\User;

class CollectionPolicy
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
        return $this->canAny($user, ['collections.view', 'collections.view', 'collection.view', 'collection.view', 'view collections']);
    }

    public function view(User $user, Collection $record): bool
    {
        return $this->canAny($user, ['collections.view', 'collections.view', 'collection.view', 'collection.view', 'view collections']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['collections.create', 'collections.create', 'collection.create', 'collection.create', 'create collections']);
    }

    public function update(User $user, Collection $record): bool
    {
        return $this->canAny($user, ['collections.update', 'collections.update', 'collection.update', 'collection.update', 'edit collections', 'update collections']);
    }

    public function delete(User $user, Collection $record): bool
    {
        return $this->canAny($user, ['collections.delete', 'collections.delete', 'collection.delete', 'collection.delete', 'delete collections']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['collections.delete', 'collections.delete', 'collection.delete', 'collection.delete', 'delete collections']);
    }

    public function forceDelete(User $user, Collection $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, Collection $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, Collection $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['collections.update', 'collections.update', 'collection.update', 'collection.update', 'edit collections', 'update collections']);
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