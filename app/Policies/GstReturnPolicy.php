<?php

namespace App\Policies;

use App\Models\GstReturn;
use App\Models\User;

class GstReturnPolicy
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
        return $this->canAny($user, ['gst-returns.view', 'gst_returns.view', 'gstReturn.view', 'gst-return.view', 'view gst returns']);
    }

    public function view(User $user, GstReturn $record): bool
    {
        return $this->canAny($user, ['gst-returns.view', 'gst_returns.view', 'gstReturn.view', 'gst-return.view', 'view gst returns']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['gst-returns.create', 'gst_returns.create', 'gstReturn.create', 'gst-return.create', 'create gst returns']);
    }

    public function update(User $user, GstReturn $record): bool
    {
        return $this->canAny($user, ['gst-returns.update', 'gst_returns.update', 'gstReturn.update', 'gst-return.update', 'edit gst returns', 'update gst returns']);
    }

    public function delete(User $user, GstReturn $record): bool
    {
        return $this->canAny($user, ['gst-returns.delete', 'gst_returns.delete', 'gstReturn.delete', 'gst-return.delete', 'delete gst returns']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['gst-returns.delete', 'gst_returns.delete', 'gstReturn.delete', 'gst-return.delete', 'delete gst returns']);
    }

    public function forceDelete(User $user, GstReturn $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, GstReturn $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, GstReturn $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['gst-returns.update', 'gst_returns.update', 'gstReturn.update', 'gst-return.update', 'edit gst returns', 'update gst returns']);
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