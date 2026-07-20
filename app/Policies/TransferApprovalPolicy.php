<?php

namespace App\Policies;

use App\Models\TransferApproval;
use App\Models\User;

class TransferApprovalPolicy
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
        return $this->canAny($user, ['transfer-approvals.view', 'transfer_approvals.view', 'transferApproval.view', 'transfer-approval.view', 'view transfer approvals']);
    }

    public function view(User $user, TransferApproval $record): bool
    {
        return $this->canAny($user, ['transfer-approvals.view', 'transfer_approvals.view', 'transferApproval.view', 'transfer-approval.view', 'view transfer approvals']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['transfer-approvals.create', 'transfer_approvals.create', 'transferApproval.create', 'transfer-approval.create', 'create transfer approvals']);
    }

    public function update(User $user, TransferApproval $record): bool
    {
        return $this->canAny($user, ['transfer-approvals.update', 'transfer_approvals.update', 'transferApproval.update', 'transfer-approval.update', 'edit transfer approvals', 'update transfer approvals']);
    }

    public function delete(User $user, TransferApproval $record): bool
    {
        return $this->canAny($user, ['transfer-approvals.delete', 'transfer_approvals.delete', 'transferApproval.delete', 'transfer-approval.delete', 'delete transfer approvals']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['transfer-approvals.delete', 'transfer_approvals.delete', 'transferApproval.delete', 'transfer-approval.delete', 'delete transfer approvals']);
    }

    public function forceDelete(User $user, TransferApproval $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, TransferApproval $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, TransferApproval $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['transfer-approvals.update', 'transfer_approvals.update', 'transferApproval.update', 'transfer-approval.update', 'edit transfer approvals', 'update transfer approvals']);
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