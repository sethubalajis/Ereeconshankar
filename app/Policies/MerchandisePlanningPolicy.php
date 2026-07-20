<?php

namespace App\Policies;

use App\Models\MerchandisePlanning;
use App\Models\User;

class MerchandisePlanningPolicy
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
        return $this->canAny($user, ['merchandise-plannings.view', 'merchandise_plannings.view', 'merchandisePlanning.view', 'merchandise-planning.view', 'view merchandise plannings']);
    }

    public function view(User $user, MerchandisePlanning $record): bool
    {
        return $this->canAny($user, ['merchandise-plannings.view', 'merchandise_plannings.view', 'merchandisePlanning.view', 'merchandise-planning.view', 'view merchandise plannings']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['merchandise-plannings.create', 'merchandise_plannings.create', 'merchandisePlanning.create', 'merchandise-planning.create', 'create merchandise plannings']);
    }

    public function update(User $user, MerchandisePlanning $record): bool
    {
        return $this->canAny($user, ['merchandise-plannings.update', 'merchandise_plannings.update', 'merchandisePlanning.update', 'merchandise-planning.update', 'edit merchandise plannings', 'update merchandise plannings']);
    }

    public function delete(User $user, MerchandisePlanning $record): bool
    {
        return $this->canAny($user, ['merchandise-plannings.delete', 'merchandise_plannings.delete', 'merchandisePlanning.delete', 'merchandise-planning.delete', 'delete merchandise plannings']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['merchandise-plannings.delete', 'merchandise_plannings.delete', 'merchandisePlanning.delete', 'merchandise-planning.delete', 'delete merchandise plannings']);
    }

    public function forceDelete(User $user, MerchandisePlanning $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, MerchandisePlanning $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, MerchandisePlanning $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['merchandise-plannings.update', 'merchandise_plannings.update', 'merchandisePlanning.update', 'merchandise-planning.update', 'edit merchandise plannings', 'update merchandise plannings']);
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