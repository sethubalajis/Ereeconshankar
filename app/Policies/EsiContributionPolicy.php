<?php

namespace App\Policies;

use App\Models\EsiContribution;
use App\Models\User;

class EsiContributionPolicy
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
        return $this->canAny($user, ['esi-contributions.view', 'esi_contributions.view', 'esiContribution.view', 'esi-contribution.view', 'view esi contributions']);
    }

    public function view(User $user, EsiContribution $record): bool
    {
        return $this->canAny($user, ['esi-contributions.view', 'esi_contributions.view', 'esiContribution.view', 'esi-contribution.view', 'view esi contributions']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['esi-contributions.create', 'esi_contributions.create', 'esiContribution.create', 'esi-contribution.create', 'create esi contributions']);
    }

    public function update(User $user, EsiContribution $record): bool
    {
        return $this->canAny($user, ['esi-contributions.update', 'esi_contributions.update', 'esiContribution.update', 'esi-contribution.update', 'edit esi contributions', 'update esi contributions']);
    }

    public function delete(User $user, EsiContribution $record): bool
    {
        return $this->canAny($user, ['esi-contributions.delete', 'esi_contributions.delete', 'esiContribution.delete', 'esi-contribution.delete', 'delete esi contributions']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['esi-contributions.delete', 'esi_contributions.delete', 'esiContribution.delete', 'esi-contribution.delete', 'delete esi contributions']);
    }

    public function forceDelete(User $user, EsiContribution $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, EsiContribution $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, EsiContribution $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['esi-contributions.update', 'esi_contributions.update', 'esiContribution.update', 'esi-contribution.update', 'edit esi contributions', 'update esi contributions']);
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