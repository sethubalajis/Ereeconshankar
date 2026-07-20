<?php

namespace App\Policies;

use App\Models\EWayBill;
use App\Models\User;

class EWayBillPolicy
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
        return $this->canAny($user, ['e-way-bills.view', 'e_way_bills.view', 'eWayBill.view', 'e-way-bill.view', 'view e way bills']);
    }

    public function view(User $user, EWayBill $record): bool
    {
        return $this->canAny($user, ['e-way-bills.view', 'e_way_bills.view', 'eWayBill.view', 'e-way-bill.view', 'view e way bills']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['e-way-bills.create', 'e_way_bills.create', 'eWayBill.create', 'e-way-bill.create', 'create e way bills']);
    }

    public function update(User $user, EWayBill $record): bool
    {
        return $this->canAny($user, ['e-way-bills.update', 'e_way_bills.update', 'eWayBill.update', 'e-way-bill.update', 'edit e way bills', 'update e way bills']);
    }

    public function delete(User $user, EWayBill $record): bool
    {
        return $this->canAny($user, ['e-way-bills.delete', 'e_way_bills.delete', 'eWayBill.delete', 'e-way-bill.delete', 'delete e way bills']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['e-way-bills.delete', 'e_way_bills.delete', 'eWayBill.delete', 'e-way-bill.delete', 'delete e way bills']);
    }

    public function forceDelete(User $user, EWayBill $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, EWayBill $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, EWayBill $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['e-way-bills.update', 'e_way_bills.update', 'eWayBill.update', 'e-way-bill.update', 'edit e way bills', 'update e way bills']);
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