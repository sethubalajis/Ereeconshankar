<?php

namespace App\Policies;

use App\Models\Color;
use App\Models\User;

class ColorPolicy
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
        return $this->canAny($user, ['colors.view', 'colors.view', 'color.view', 'color.view', 'view colors']);
    }

    public function view(User $user, Color $record): bool
    {
        return $this->canAny($user, ['colors.view', 'colors.view', 'color.view', 'color.view', 'view colors']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['colors.create', 'colors.create', 'color.create', 'color.create', 'create colors']);
    }

    public function update(User $user, Color $record): bool
    {
        return $this->canAny($user, ['colors.update', 'colors.update', 'color.update', 'color.update', 'edit colors', 'update colors']);
    }

    public function delete(User $user, Color $record): bool
    {
        return $this->canAny($user, ['colors.delete', 'colors.delete', 'color.delete', 'color.delete', 'delete colors']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['colors.delete', 'colors.delete', 'color.delete', 'color.delete', 'delete colors']);
    }

    public function forceDelete(User $user, Color $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, Color $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, Color $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['colors.update', 'colors.update', 'color.update', 'color.update', 'edit colors', 'update colors']);
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