<?php

namespace App\Policies;

use App\Models\PfContribution;
use App\Models\User;

class PfContributionPolicy
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
        return $this->canAny($user, ['pf-contributions.view', 'pf_contributions.view', 'pfContribution.view', 'pf-contribution.view', 'view pf contributions']);
    }

    public function view(User $user, PfContribution $record): bool
    {
        return $this->canAny($user, ['pf-contributions.view', 'pf_contributions.view', 'pfContribution.view', 'pf-contribution.view', 'view pf contributions']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['pf-contributions.create', 'pf_contributions.create', 'pfContribution.create', 'pf-contribution.create', 'create pf contributions']);
    }

    public function update(User $user, PfContribution $record): bool
    {
        return $this->canAny($user, ['pf-contributions.update', 'pf_contributions.update', 'pfContribution.update', 'pf-contribution.update', 'edit pf contributions', 'update pf contributions']);
    }

    public function delete(User $user, PfContribution $record): bool
    {
        return $this->canAny($user, ['pf-contributions.delete', 'pf_contributions.delete', 'pfContribution.delete', 'pf-contribution.delete', 'delete pf contributions']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['pf-contributions.delete', 'pf_contributions.delete', 'pfContribution.delete', 'pf-contribution.delete', 'delete pf contributions']);
    }

    public function forceDelete(User $user, PfContribution $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, PfContribution $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, PfContribution $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['pf-contributions.update', 'pf_contributions.update', 'pfContribution.update', 'pf-contribution.update', 'edit pf contributions', 'update pf contributions']);
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