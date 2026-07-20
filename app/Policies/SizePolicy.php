<?php

namespace App\Policies;

use App\Models\Size;
use App\Models\User;

class SizePolicy
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
        return $this->canAny($user, ['sizes.view', 'sizes.view', 'size.view', 'size.view', 'view sizes']);
    }

    public function view(User $user, Size $record): bool
    {
        return $this->canAny($user, ['sizes.view', 'sizes.view', 'size.view', 'size.view', 'view sizes']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['sizes.create', 'sizes.create', 'size.create', 'size.create', 'create sizes']);
    }

    public function update(User $user, Size $record): bool
    {
        return $this->canAny($user, ['sizes.update', 'sizes.update', 'size.update', 'size.update', 'edit sizes', 'update sizes']);
    }

    public function delete(User $user, Size $record): bool
    {
        return $this->canAny($user, ['sizes.delete', 'sizes.delete', 'size.delete', 'size.delete', 'delete sizes']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['sizes.delete', 'sizes.delete', 'size.delete', 'size.delete', 'delete sizes']);
    }

    public function forceDelete(User $user, Size $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, Size $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, Size $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['sizes.update', 'sizes.update', 'size.update', 'size.update', 'edit sizes', 'update sizes']);
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