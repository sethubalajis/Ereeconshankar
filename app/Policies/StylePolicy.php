<?php

namespace App\Policies;

use App\Models\Style;
use App\Models\User;

class StylePolicy
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
        return $this->canAny($user, ['styles.view', 'styles.view', 'style.view', 'style.view', 'view styles']);
    }

    public function view(User $user, Style $record): bool
    {
        return $this->canAny($user, ['styles.view', 'styles.view', 'style.view', 'style.view', 'view styles']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['styles.create', 'styles.create', 'style.create', 'style.create', 'create styles']);
    }

    public function update(User $user, Style $record): bool
    {
        return $this->canAny($user, ['styles.update', 'styles.update', 'style.update', 'style.update', 'edit styles', 'update styles']);
    }

    public function delete(User $user, Style $record): bool
    {
        return $this->canAny($user, ['styles.delete', 'styles.delete', 'style.delete', 'style.delete', 'delete styles']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['styles.delete', 'styles.delete', 'style.delete', 'style.delete', 'delete styles']);
    }

    public function forceDelete(User $user, Style $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, Style $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, Style $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['styles.update', 'styles.update', 'style.update', 'style.update', 'edit styles', 'update styles']);
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