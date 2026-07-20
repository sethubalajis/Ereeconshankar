<?php

namespace App\Policies;

use App\Models\TaxGroup;
use App\Models\User;

class TaxGroupPolicy
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
        return $this->canAny($user, ['tax-groups.view', 'tax_groups.view', 'taxGroup.view', 'tax-group.view', 'view tax groups']);
    }

    public function view(User $user, TaxGroup $taxGroup): bool
    {
        return $this->canAny($user, ['tax-groups.view', 'tax_groups.view', 'taxGroup.view', 'tax-group.view', 'view tax groups']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['tax-groups.create', 'tax_groups.create', 'taxGroup.create', 'tax-group.create', 'create tax groups']);
    }

    public function update(User $user, TaxGroup $taxGroup): bool
    {
        return $this->canAny($user, ['tax-groups.update', 'tax_groups.update', 'taxGroup.update', 'tax-group.update', 'edit tax groups', 'update tax groups']);
    }

    public function delete(User $user, TaxGroup $taxGroup): bool
    {
        return $this->canAny($user, ['tax-groups.delete', 'tax_groups.delete', 'taxGroup.delete', 'tax-group.delete', 'delete tax groups']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['tax-groups.delete', 'tax_groups.delete', 'taxGroup.delete', 'tax-group.delete', 'delete tax groups']);
    }

    public function forceDelete(User $user, TaxGroup $taxGroup): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, TaxGroup $taxGroup): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, TaxGroup $taxGroup): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['tax-groups.update', 'tax_groups.update', 'taxGroup.update', 'tax-group.update', 'edit tax groups', 'update tax groups']);
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
