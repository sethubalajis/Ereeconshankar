<?php

namespace App\Policies;

use App\Models\EInvoice;
use App\Models\User;

class EInvoicePolicy
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
        return $this->canAny($user, ['e-invoices.view', 'e_invoices.view', 'eInvoice.view', 'e-invoice.view', 'view e invoices']);
    }

    public function view(User $user, EInvoice $record): bool
    {
        return $this->canAny($user, ['e-invoices.view', 'e_invoices.view', 'eInvoice.view', 'e-invoice.view', 'view e invoices']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['e-invoices.create', 'e_invoices.create', 'eInvoice.create', 'e-invoice.create', 'create e invoices']);
    }

    public function update(User $user, EInvoice $record): bool
    {
        return $this->canAny($user, ['e-invoices.update', 'e_invoices.update', 'eInvoice.update', 'e-invoice.update', 'edit e invoices', 'update e invoices']);
    }

    public function delete(User $user, EInvoice $record): bool
    {
        return $this->canAny($user, ['e-invoices.delete', 'e_invoices.delete', 'eInvoice.delete', 'e-invoice.delete', 'delete e invoices']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['e-invoices.delete', 'e_invoices.delete', 'eInvoice.delete', 'e-invoice.delete', 'delete e invoices']);
    }

    public function forceDelete(User $user, EInvoice $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, EInvoice $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, EInvoice $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['e-invoices.update', 'e_invoices.update', 'eInvoice.update', 'e-invoice.update', 'edit e invoices', 'update e invoices']);
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