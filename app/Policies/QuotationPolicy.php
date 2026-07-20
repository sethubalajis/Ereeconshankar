<?php

namespace App\Policies;

use App\Models\Quotation;
use App\Models\User;

class QuotationPolicy
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
        return $this->canAny($user, ['quotations.view', 'quotations.view', 'quotation.view', 'quotation.view', 'view quotations']);
    }

    public function view(User $user, Quotation $record): bool
    {
        return $this->canAny($user, ['quotations.view', 'quotations.view', 'quotation.view', 'quotation.view', 'view quotations']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['quotations.create', 'quotations.create', 'quotation.create', 'quotation.create', 'create quotations']);
    }

    public function update(User $user, Quotation $record): bool
    {
        return $this->canAny($user, ['quotations.update', 'quotations.update', 'quotation.update', 'quotation.update', 'edit quotations', 'update quotations']);
    }

    public function delete(User $user, Quotation $record): bool
    {
        return $this->canAny($user, ['quotations.delete', 'quotations.delete', 'quotation.delete', 'quotation.delete', 'delete quotations']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['quotations.delete', 'quotations.delete', 'quotation.delete', 'quotation.delete', 'delete quotations']);
    }

    public function forceDelete(User $user, Quotation $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, Quotation $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, Quotation $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['quotations.update', 'quotations.update', 'quotation.update', 'quotation.update', 'edit quotations', 'update quotations']);
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