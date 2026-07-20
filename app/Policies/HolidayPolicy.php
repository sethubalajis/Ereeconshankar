<?php

namespace App\Policies;

use App\Models\Holiday;
use App\Models\User;

class HolidayPolicy
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
        return $this->canAny($user, ['holidays.view', 'holidays.view', 'holiday.view', 'holiday.view', 'view holidays']);
    }

    public function view(User $user, Holiday $record): bool
    {
        return $this->canAny($user, ['holidays.view', 'holidays.view', 'holiday.view', 'holiday.view', 'view holidays']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['holidays.create', 'holidays.create', 'holiday.create', 'holiday.create', 'create holidays']);
    }

    public function update(User $user, Holiday $record): bool
    {
        return $this->canAny($user, ['holidays.update', 'holidays.update', 'holiday.update', 'holiday.update', 'edit holidays', 'update holidays']);
    }

    public function delete(User $user, Holiday $record): bool
    {
        return $this->canAny($user, ['holidays.delete', 'holidays.delete', 'holiday.delete', 'holiday.delete', 'delete holidays']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['holidays.delete', 'holidays.delete', 'holiday.delete', 'holiday.delete', 'delete holidays']);
    }

    public function forceDelete(User $user, Holiday $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, Holiday $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, Holiday $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['holidays.update', 'holidays.update', 'holiday.update', 'holiday.update', 'edit holidays', 'update holidays']);
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