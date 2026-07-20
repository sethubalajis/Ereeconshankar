<?php

namespace App\Policies;

use App\Models\Rfq;
use App\Models\User;

class RfqPolicy
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
        return $this->canAny($user, ['rfqs.view', 'rfqs.view', 'rfq.view', 'rfq.view', 'view rfqs']);
    }

    public function view(User $user, Rfq $record): bool
    {
        return $this->canAny($user, ['rfqs.view', 'rfqs.view', 'rfq.view', 'rfq.view', 'view rfqs']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['rfqs.create', 'rfqs.create', 'rfq.create', 'rfq.create', 'create rfqs']);
    }

    public function update(User $user, Rfq $record): bool
    {
        return $this->canAny($user, ['rfqs.update', 'rfqs.update', 'rfq.update', 'rfq.update', 'edit rfqs', 'update rfqs']);
    }

    public function delete(User $user, Rfq $record): bool
    {
        return $this->canAny($user, ['rfqs.delete', 'rfqs.delete', 'rfq.delete', 'rfq.delete', 'delete rfqs']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['rfqs.delete', 'rfqs.delete', 'rfq.delete', 'rfq.delete', 'delete rfqs']);
    }

    public function forceDelete(User $user, Rfq $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, Rfq $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, Rfq $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['rfqs.update', 'rfqs.update', 'rfq.update', 'rfq.update', 'edit rfqs', 'update rfqs']);
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