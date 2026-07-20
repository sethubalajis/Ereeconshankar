<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;

class CustomerPolicy
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
        return $this->canAny($user, ['customers.view', 'customers.view', 'customer.view', 'customer.view', 'view customers']);
    }

    public function view(User $user, Customer $record): bool
    {
        return $this->canAny($user, ['customers.view', 'customers.view', 'customer.view', 'customer.view', 'view customers']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['customers.create', 'customers.create', 'customer.create', 'customer.create', 'create customers']);
    }

    public function update(User $user, Customer $record): bool
    {
        return $this->canAny($user, ['customers.update', 'customers.update', 'customer.update', 'customer.update', 'edit customers', 'update customers']);
    }

    public function delete(User $user, Customer $record): bool
    {
        return $this->canAny($user, ['customers.delete', 'customers.delete', 'customer.delete', 'customer.delete', 'delete customers']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['customers.delete', 'customers.delete', 'customer.delete', 'customer.delete', 'delete customers']);
    }

    public function forceDelete(User $user, Customer $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, Customer $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, Customer $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['customers.update', 'customers.update', 'customer.update', 'customer.update', 'edit customers', 'update customers']);
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