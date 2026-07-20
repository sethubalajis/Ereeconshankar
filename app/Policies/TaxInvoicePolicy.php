<?php

namespace App\Policies;

use App\Models\TaxInvoice;
use App\Models\User;

class TaxInvoicePolicy
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
        return $this->canAny($user, ['tax-invoices.view', 'tax_invoices.view', 'taxInvoice.view', 'tax-invoice.view', 'view tax invoices']);
    }

    public function view(User $user, TaxInvoice $record): bool
    {
        return $this->canAny($user, ['tax-invoices.view', 'tax_invoices.view', 'taxInvoice.view', 'tax-invoice.view', 'view tax invoices']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['tax-invoices.create', 'tax_invoices.create', 'taxInvoice.create', 'tax-invoice.create', 'create tax invoices']);
    }

    public function update(User $user, TaxInvoice $record): bool
    {
        return $this->canAny($user, ['tax-invoices.update', 'tax_invoices.update', 'taxInvoice.update', 'tax-invoice.update', 'edit tax invoices', 'update tax invoices']);
    }

    public function delete(User $user, TaxInvoice $record): bool
    {
        return $this->canAny($user, ['tax-invoices.delete', 'tax_invoices.delete', 'taxInvoice.delete', 'tax-invoice.delete', 'delete tax invoices']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['tax-invoices.delete', 'tax_invoices.delete', 'taxInvoice.delete', 'tax-invoice.delete', 'delete tax invoices']);
    }

    public function forceDelete(User $user, TaxInvoice $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, TaxInvoice $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, TaxInvoice $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['tax-invoices.update', 'tax_invoices.update', 'taxInvoice.update', 'tax-invoice.update', 'edit tax invoices', 'update tax invoices']);
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