<?php

namespace App\Policies;

use App\Models\ProfessionalTax;
use App\Models\User;

class ProfessionalTaxPolicy
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
        return $this->canAny($user, ['professional-taxes.view', 'professional_taxes.view', 'professionalTax.view', 'professional-tax.view', 'view professional taxes']);
    }

    public function view(User $user, ProfessionalTax $record): bool
    {
        return $this->canAny($user, ['professional-taxes.view', 'professional_taxes.view', 'professionalTax.view', 'professional-tax.view', 'view professional taxes']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['professional-taxes.create', 'professional_taxes.create', 'professionalTax.create', 'professional-tax.create', 'create professional taxes']);
    }

    public function update(User $user, ProfessionalTax $record): bool
    {
        return $this->canAny($user, ['professional-taxes.update', 'professional_taxes.update', 'professionalTax.update', 'professional-tax.update', 'edit professional taxes', 'update professional taxes']);
    }

    public function delete(User $user, ProfessionalTax $record): bool
    {
        return $this->canAny($user, ['professional-taxes.delete', 'professional_taxes.delete', 'professionalTax.delete', 'professional-tax.delete', 'delete professional taxes']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['professional-taxes.delete', 'professional_taxes.delete', 'professionalTax.delete', 'professional-tax.delete', 'delete professional taxes']);
    }

    public function forceDelete(User $user, ProfessionalTax $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, ProfessionalTax $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, ProfessionalTax $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['professional-taxes.update', 'professional_taxes.update', 'professionalTax.update', 'professional-tax.update', 'edit professional taxes', 'update professional taxes']);
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