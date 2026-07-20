<?php

namespace App\Policies;

use App\Models\AccountGroup;
use App\Models\User;

class AccountGroupPolicy
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
        return $this->canAny($user, ['account-groups.view', 'account_groups.view', 'accountGroup.view', 'account-group.view', 'view account groups']);
    }

    public function view(User $user, AccountGroup $record): bool
    {
        return $this->canAny($user, ['account-groups.view', 'account_groups.view', 'accountGroup.view', 'account-group.view', 'view account groups']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['account-groups.create', 'account_groups.create', 'accountGroup.create', 'account-group.create', 'create account groups']);
    }

    public function update(User $user, AccountGroup $record): bool
    {
        return $this->canAny($user, ['account-groups.update', 'account_groups.update', 'accountGroup.update', 'account-group.update', 'edit account groups', 'update account groups']);
    }

    public function delete(User $user, AccountGroup $record): bool
    {
        return $this->canAny($user, ['account-groups.delete', 'account_groups.delete', 'accountGroup.delete', 'account-group.delete', 'delete account groups']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['account-groups.delete', 'account_groups.delete', 'accountGroup.delete', 'account-group.delete', 'delete account groups']);
    }

    public function forceDelete(User $user, AccountGroup $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, AccountGroup $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, AccountGroup $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['account-groups.update', 'account_groups.update', 'accountGroup.update', 'account-group.update', 'edit account groups', 'update account groups']);
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