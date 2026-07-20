<?php

namespace App\Policies;

use App\Models\CostCenter;
use App\Models\User;

class CostCenterPolicy
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
        return $this->canAny($user, ['cost-centers.view', 'cost_centers.view', 'costCenter.view', 'cost-center.view', 'view cost centers']);
    }

    public function view(User $user, CostCenter $costCenter): bool
    {
        return $this->canAny($user, ['cost-centers.view', 'cost_centers.view', 'costCenter.view', 'cost-center.view', 'view cost centers']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['cost-centers.create', 'cost_centers.create', 'costCenter.create', 'cost-center.create', 'create cost centers']);
    }

    public function update(User $user, CostCenter $costCenter): bool
    {
        return $this->canAny($user, ['cost-centers.update', 'cost_centers.update', 'costCenter.update', 'cost-center.update', 'edit cost centers', 'update cost centers']);
    }

    public function delete(User $user, CostCenter $costCenter): bool
    {
        return $this->canAny($user, ['cost-centers.delete', 'cost_centers.delete', 'costCenter.delete', 'cost-center.delete', 'delete cost centers']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['cost-centers.delete', 'cost_centers.delete', 'costCenter.delete', 'cost-center.delete', 'delete cost centers']);
    }

    public function forceDelete(User $user, CostCenter $costCenter): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, CostCenter $costCenter): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, CostCenter $costCenter): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['cost-centers.update', 'cost_centers.update', 'costCenter.update', 'cost-center.update', 'edit cost centers', 'update cost centers']);
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
