<?php

namespace App\Policies;

use App\Models\InvestmentType;
use App\Models\User;

class InvestmentTypePolicy
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
        return $this->canAny($user, ['investment-types.view', 'investment_types.view', 'investmentType.view', 'investment-type.view', 'view investment types']);
    }

    public function view(User $user, InvestmentType $record): bool
    {
        return $this->canAny($user, ['investment-types.view', 'investment_types.view', 'investmentType.view', 'investment-type.view', 'view investment types']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['investment-types.create', 'investment_types.create', 'investmentType.create', 'investment-type.create', 'create investment types']);
    }

    public function update(User $user, InvestmentType $record): bool
    {
        return $this->canAny($user, ['investment-types.update', 'investment_types.update', 'investmentType.update', 'investment-type.update', 'edit investment types', 'update investment types']);
    }

    public function delete(User $user, InvestmentType $record): bool
    {
        return $this->canAny($user, ['investment-types.delete', 'investment_types.delete', 'investmentType.delete', 'investment-type.delete', 'delete investment types']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['investment-types.delete', 'investment_types.delete', 'investmentType.delete', 'investment-type.delete', 'delete investment types']);
    }

    public function forceDelete(User $user, InvestmentType $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, InvestmentType $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, InvestmentType $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['investment-types.update', 'investment_types.update', 'investmentType.update', 'investment-type.update', 'edit investment types', 'update investment types']);
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