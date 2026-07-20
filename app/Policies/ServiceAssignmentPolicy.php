<?php

namespace App\Policies;

use App\Models\ServiceAssignment;
use App\Models\User;

class ServiceAssignmentPolicy
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
        return $this->canAny($user, ['service-assignments.view', 'service_assignments.view', 'serviceAssignment.view', 'service-assignment.view', 'view service assignments']);
    }

    public function view(User $user, ServiceAssignment $record): bool
    {
        return $this->canAny($user, ['service-assignments.view', 'service_assignments.view', 'serviceAssignment.view', 'service-assignment.view', 'view service assignments']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['service-assignments.create', 'service_assignments.create', 'serviceAssignment.create', 'service-assignment.create', 'create service assignments']);
    }

    public function update(User $user, ServiceAssignment $record): bool
    {
        return $this->canAny($user, ['service-assignments.update', 'service_assignments.update', 'serviceAssignment.update', 'service-assignment.update', 'edit service assignments', 'update service assignments']);
    }

    public function delete(User $user, ServiceAssignment $record): bool
    {
        return $this->canAny($user, ['service-assignments.delete', 'service_assignments.delete', 'serviceAssignment.delete', 'service-assignment.delete', 'delete service assignments']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['service-assignments.delete', 'service_assignments.delete', 'serviceAssignment.delete', 'service-assignment.delete', 'delete service assignments']);
    }

    public function forceDelete(User $user, ServiceAssignment $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, ServiceAssignment $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, ServiceAssignment $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['service-assignments.update', 'service_assignments.update', 'serviceAssignment.update', 'service-assignment.update', 'edit service assignments', 'update service assignments']);
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