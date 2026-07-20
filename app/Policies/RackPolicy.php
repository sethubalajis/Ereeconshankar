<?php

namespace App\Policies;

use App\Models\Rack;
use App\Models\User;

class RackPolicy
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
        return $this->canAny($user, ['racks.view', 'racks.view', 'rack.view', 'rack.view', 'view racks']);
    }

    public function view(User $user, Rack $record): bool
    {
        return $this->canAny($user, ['racks.view', 'racks.view', 'rack.view', 'rack.view', 'view racks']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['racks.create', 'racks.create', 'rack.create', 'rack.create', 'create racks']);
    }

    public function update(User $user, Rack $record): bool
    {
        return $this->canAny($user, ['racks.update', 'racks.update', 'rack.update', 'rack.update', 'edit racks', 'update racks']);
    }

    public function delete(User $user, Rack $record): bool
    {
        return $this->canAny($user, ['racks.delete', 'racks.delete', 'rack.delete', 'rack.delete', 'delete racks']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['racks.delete', 'racks.delete', 'rack.delete', 'rack.delete', 'delete racks']);
    }

    public function forceDelete(User $user, Rack $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, Rack $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, Rack $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['racks.update', 'racks.update', 'rack.update', 'rack.update', 'edit racks', 'update racks']);
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