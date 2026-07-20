<?php

namespace App\Policies;

use App\Models\PosCounter;
use App\Models\User;

class PosCounterPolicy
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
        return $this->canAny($user, ['pos-counters.view', 'pos_counters.view', 'posCounter.view', 'pos-counter.view', 'view pos counters']);
    }

    public function view(User $user, PosCounter $record): bool
    {
        return $this->canAny($user, ['pos-counters.view', 'pos_counters.view', 'posCounter.view', 'pos-counter.view', 'view pos counters']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['pos-counters.create', 'pos_counters.create', 'posCounter.create', 'pos-counter.create', 'create pos counters']);
    }

    public function update(User $user, PosCounter $record): bool
    {
        return $this->canAny($user, ['pos-counters.update', 'pos_counters.update', 'posCounter.update', 'pos-counter.update', 'edit pos counters', 'update pos counters']);
    }

    public function delete(User $user, PosCounter $record): bool
    {
        return $this->canAny($user, ['pos-counters.delete', 'pos_counters.delete', 'posCounter.delete', 'pos-counter.delete', 'delete pos counters']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['pos-counters.delete', 'pos_counters.delete', 'posCounter.delete', 'pos-counter.delete', 'delete pos counters']);
    }

    public function forceDelete(User $user, PosCounter $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, PosCounter $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, PosCounter $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['pos-counters.update', 'pos_counters.update', 'posCounter.update', 'pos-counter.update', 'edit pos counters', 'update pos counters']);
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