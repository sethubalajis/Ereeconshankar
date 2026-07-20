<?php

namespace App\Policies;

use App\Models\TechnicianVisit;
use App\Models\User;

class TechnicianVisitPolicy
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
        return $this->canAny($user, ['technician-visits.view', 'technician_visits.view', 'technicianVisit.view', 'technician-visit.view', 'view technician visits']);
    }

    public function view(User $user, TechnicianVisit $record): bool
    {
        return $this->canAny($user, ['technician-visits.view', 'technician_visits.view', 'technicianVisit.view', 'technician-visit.view', 'view technician visits']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['technician-visits.create', 'technician_visits.create', 'technicianVisit.create', 'technician-visit.create', 'create technician visits']);
    }

    public function update(User $user, TechnicianVisit $record): bool
    {
        return $this->canAny($user, ['technician-visits.update', 'technician_visits.update', 'technicianVisit.update', 'technician-visit.update', 'edit technician visits', 'update technician visits']);
    }

    public function delete(User $user, TechnicianVisit $record): bool
    {
        return $this->canAny($user, ['technician-visits.delete', 'technician_visits.delete', 'technicianVisit.delete', 'technician-visit.delete', 'delete technician visits']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['technician-visits.delete', 'technician_visits.delete', 'technicianVisit.delete', 'technician-visit.delete', 'delete technician visits']);
    }

    public function forceDelete(User $user, TechnicianVisit $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, TechnicianVisit $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, TechnicianVisit $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['technician-visits.update', 'technician_visits.update', 'technicianVisit.update', 'technician-visit.update', 'edit technician visits', 'update technician visits']);
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