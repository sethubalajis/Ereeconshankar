<?php

namespace App\Policies;

use App\Models\Design;
use App\Models\User;

class DesignPolicy
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
        return $this->canAny($user, ['designs.view', 'designs.view', 'design.view', 'design.view', 'view designs']);
    }

    public function view(User $user, Design $record): bool
    {
        return $this->canAny($user, ['designs.view', 'designs.view', 'design.view', 'design.view', 'view designs']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['designs.create', 'designs.create', 'design.create', 'design.create', 'create designs']);
    }

    public function update(User $user, Design $record): bool
    {
        return $this->canAny($user, ['designs.update', 'designs.update', 'design.update', 'design.update', 'edit designs', 'update designs']);
    }

    public function delete(User $user, Design $record): bool
    {
        return $this->canAny($user, ['designs.delete', 'designs.delete', 'design.delete', 'design.delete', 'delete designs']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['designs.delete', 'designs.delete', 'design.delete', 'design.delete', 'delete designs']);
    }

    public function forceDelete(User $user, Design $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, Design $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, Design $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['designs.update', 'designs.update', 'design.update', 'design.update', 'edit designs', 'update designs']);
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