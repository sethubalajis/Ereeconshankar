<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\User;

class DepartmentPolicy
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
        return $this->canAny($user, ['departments.view', 'departments.view', 'department.view', 'department.view', 'view departments']);
    }

    public function view(User $user, Department $record): bool
    {
        return $this->canAny($user, ['departments.view', 'departments.view', 'department.view', 'department.view', 'view departments']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['departments.create', 'departments.create', 'department.create', 'department.create', 'create departments']);
    }

    public function update(User $user, Department $record): bool
    {
        return $this->canAny($user, ['departments.update', 'departments.update', 'department.update', 'department.update', 'edit departments', 'update departments']);
    }

    public function delete(User $user, Department $record): bool
    {
        return $this->canAny($user, ['departments.delete', 'departments.delete', 'department.delete', 'department.delete', 'delete departments']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['departments.delete', 'departments.delete', 'department.delete', 'department.delete', 'delete departments']);
    }

    public function forceDelete(User $user, Department $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, Department $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, Department $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['departments.update', 'departments.update', 'department.update', 'department.update', 'edit departments', 'update departments']);
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