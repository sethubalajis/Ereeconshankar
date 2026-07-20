<?php

namespace App\Policies;

use App\Models\TransferRequest;
use App\Models\User;

class TransferRequestPolicy
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
        return $this->canAny($user, ['transfer-requests.view', 'transfer_requests.view', 'transferRequest.view', 'transfer-request.view', 'view transfer requests']);
    }

    public function view(User $user, TransferRequest $record): bool
    {
        return $this->canAny($user, ['transfer-requests.view', 'transfer_requests.view', 'transferRequest.view', 'transfer-request.view', 'view transfer requests']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['transfer-requests.create', 'transfer_requests.create', 'transferRequest.create', 'transfer-request.create', 'create transfer requests']);
    }

    public function update(User $user, TransferRequest $record): bool
    {
        return $this->canAny($user, ['transfer-requests.update', 'transfer_requests.update', 'transferRequest.update', 'transfer-request.update', 'edit transfer requests', 'update transfer requests']);
    }

    public function delete(User $user, TransferRequest $record): bool
    {
        return $this->canAny($user, ['transfer-requests.delete', 'transfer_requests.delete', 'transferRequest.delete', 'transfer-request.delete', 'delete transfer requests']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['transfer-requests.delete', 'transfer_requests.delete', 'transferRequest.delete', 'transfer-request.delete', 'delete transfer requests']);
    }

    public function forceDelete(User $user, TransferRequest $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, TransferRequest $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, TransferRequest $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['transfer-requests.update', 'transfer_requests.update', 'transferRequest.update', 'transfer-request.update', 'edit transfer requests', 'update transfer requests']);
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