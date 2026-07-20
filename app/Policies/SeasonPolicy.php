<?php

namespace App\Policies;

use App\Models\Season;
use App\Models\User;

class SeasonPolicy
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
        return $this->canAny($user, ['seasons.view', 'seasons.view', 'season.view', 'season.view', 'view seasons']);
    }

    public function view(User $user, Season $record): bool
    {
        return $this->canAny($user, ['seasons.view', 'seasons.view', 'season.view', 'season.view', 'view seasons']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['seasons.create', 'seasons.create', 'season.create', 'season.create', 'create seasons']);
    }

    public function update(User $user, Season $record): bool
    {
        return $this->canAny($user, ['seasons.update', 'seasons.update', 'season.update', 'season.update', 'edit seasons', 'update seasons']);
    }

    public function delete(User $user, Season $record): bool
    {
        return $this->canAny($user, ['seasons.delete', 'seasons.delete', 'season.delete', 'season.delete', 'delete seasons']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['seasons.delete', 'seasons.delete', 'season.delete', 'season.delete', 'delete seasons']);
    }

    public function forceDelete(User $user, Season $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, Season $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, Season $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['seasons.update', 'seasons.update', 'season.update', 'season.update', 'edit seasons', 'update seasons']);
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