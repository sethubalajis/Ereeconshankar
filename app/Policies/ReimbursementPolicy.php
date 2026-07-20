<?php

namespace App\Policies;

use App\Models\Reimbursement;
use App\Models\User;

class ReimbursementPolicy
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
        return $this->canAny($user, ['reimbursements.view', 'reimbursements.view', 'reimbursement.view', 'reimbursement.view', 'view reimbursements']);
    }

    public function view(User $user, Reimbursement $record): bool
    {
        return $this->canAny($user, ['reimbursements.view', 'reimbursements.view', 'reimbursement.view', 'reimbursement.view', 'view reimbursements']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['reimbursements.create', 'reimbursements.create', 'reimbursement.create', 'reimbursement.create', 'create reimbursements']);
    }

    public function update(User $user, Reimbursement $record): bool
    {
        return $this->canAny($user, ['reimbursements.update', 'reimbursements.update', 'reimbursement.update', 'reimbursement.update', 'edit reimbursements', 'update reimbursements']);
    }

    public function delete(User $user, Reimbursement $record): bool
    {
        return $this->canAny($user, ['reimbursements.delete', 'reimbursements.delete', 'reimbursement.delete', 'reimbursement.delete', 'delete reimbursements']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['reimbursements.delete', 'reimbursements.delete', 'reimbursement.delete', 'reimbursement.delete', 'delete reimbursements']);
    }

    public function forceDelete(User $user, Reimbursement $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, Reimbursement $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, Reimbursement $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['reimbursements.update', 'reimbursements.update', 'reimbursement.update', 'reimbursement.update', 'edit reimbursements', 'update reimbursements']);
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