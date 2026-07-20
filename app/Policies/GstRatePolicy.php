<?php

namespace App\Policies;

use App\Models\GstRate;
use App\Models\User;

class GstRatePolicy
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
        return $this->canAny($user, ['gst-rates.view', 'gst_rates.view', 'gstRate.view', 'gst-rate.view', 'view gst rates']);
    }

    public function view(User $user, GstRate $record): bool
    {
        return $this->canAny($user, ['gst-rates.view', 'gst_rates.view', 'gstRate.view', 'gst-rate.view', 'view gst rates']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['gst-rates.create', 'gst_rates.create', 'gstRate.create', 'gst-rate.create', 'create gst rates']);
    }

    public function update(User $user, GstRate $record): bool
    {
        return $this->canAny($user, ['gst-rates.update', 'gst_rates.update', 'gstRate.update', 'gst-rate.update', 'edit gst rates', 'update gst rates']);
    }

    public function delete(User $user, GstRate $record): bool
    {
        return $this->canAny($user, ['gst-rates.delete', 'gst_rates.delete', 'gstRate.delete', 'gst-rate.delete', 'delete gst rates']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['gst-rates.delete', 'gst_rates.delete', 'gstRate.delete', 'gst-rate.delete', 'delete gst rates']);
    }

    public function forceDelete(User $user, GstRate $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, GstRate $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, GstRate $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['gst-rates.update', 'gst_rates.update', 'gstRate.update', 'gst-rate.update', 'edit gst rates', 'update gst rates']);
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