<?php

namespace App\Policies;

use App\Models\ServiceRequest;
use App\Models\User;

class ServiceRequestPolicy
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
        return $this->canAny($user, ['service-requests.view', 'service_requests.view', 'serviceRequest.view', 'service-request.view', 'view service requests']);
    }

    public function view(User $user, ServiceRequest $record): bool
    {
        return $this->canAny($user, ['service-requests.view', 'service_requests.view', 'serviceRequest.view', 'service-request.view', 'view service requests']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['service-requests.create', 'service_requests.create', 'serviceRequest.create', 'service-request.create', 'create service requests']);
    }

    public function update(User $user, ServiceRequest $record): bool
    {
        return $this->canAny($user, ['service-requests.update', 'service_requests.update', 'serviceRequest.update', 'service-request.update', 'edit service requests', 'update service requests']);
    }

    public function delete(User $user, ServiceRequest $record): bool
    {
        return $this->canAny($user, ['service-requests.delete', 'service_requests.delete', 'serviceRequest.delete', 'service-request.delete', 'delete service requests']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['service-requests.delete', 'service_requests.delete', 'serviceRequest.delete', 'service-request.delete', 'delete service requests']);
    }

    public function forceDelete(User $user, ServiceRequest $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, ServiceRequest $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, ServiceRequest $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['service-requests.update', 'service_requests.update', 'serviceRequest.update', 'service-request.update', 'edit service requests', 'update service requests']);
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