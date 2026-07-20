<?php

namespace App\Policies;

use App\Models\CustomerGroup;
use App\Models\User;

class CustomerGroupPolicy
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
        return $this->canAny($user, ['customer-groups.view', 'customer_groups.view', 'customerGroup.view', 'customer-group.view', 'view customer groups']);
    }

    public function view(User $user, CustomerGroup $record): bool
    {
        return $this->canAny($user, ['customer-groups.view', 'customer_groups.view', 'customerGroup.view', 'customer-group.view', 'view customer groups']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['customer-groups.create', 'customer_groups.create', 'customerGroup.create', 'customer-group.create', 'create customer groups']);
    }

    public function update(User $user, CustomerGroup $record): bool
    {
        return $this->canAny($user, ['customer-groups.update', 'customer_groups.update', 'customerGroup.update', 'customer-group.update', 'edit customer groups', 'update customer groups']);
    }

    public function delete(User $user, CustomerGroup $record): bool
    {
        return $this->canAny($user, ['customer-groups.delete', 'customer_groups.delete', 'customerGroup.delete', 'customer-group.delete', 'delete customer groups']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['customer-groups.delete', 'customer_groups.delete', 'customerGroup.delete', 'customer-group.delete', 'delete customer groups']);
    }

    public function forceDelete(User $user, CustomerGroup $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, CustomerGroup $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, CustomerGroup $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['customer-groups.update', 'customer_groups.update', 'customerGroup.update', 'customer-group.update', 'edit customer groups', 'update customer groups']);
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