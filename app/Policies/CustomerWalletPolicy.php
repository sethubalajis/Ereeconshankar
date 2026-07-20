<?php

namespace App\Policies;

use App\Models\CustomerWallet;
use App\Models\User;

class CustomerWalletPolicy
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
        return $this->canAny($user, ['customer-wallets.view', 'customer_wallets.view', 'customerWallet.view', 'customer-wallet.view', 'view customer wallets']);
    }

    public function view(User $user, CustomerWallet $record): bool
    {
        return $this->canAny($user, ['customer-wallets.view', 'customer_wallets.view', 'customerWallet.view', 'customer-wallet.view', 'view customer wallets']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['customer-wallets.create', 'customer_wallets.create', 'customerWallet.create', 'customer-wallet.create', 'create customer wallets']);
    }

    public function update(User $user, CustomerWallet $record): bool
    {
        return $this->canAny($user, ['customer-wallets.update', 'customer_wallets.update', 'customerWallet.update', 'customer-wallet.update', 'edit customer wallets', 'update customer wallets']);
    }

    public function delete(User $user, CustomerWallet $record): bool
    {
        return $this->canAny($user, ['customer-wallets.delete', 'customer_wallets.delete', 'customerWallet.delete', 'customer-wallet.delete', 'delete customer wallets']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['customer-wallets.delete', 'customer_wallets.delete', 'customerWallet.delete', 'customer-wallet.delete', 'delete customer wallets']);
    }

    public function forceDelete(User $user, CustomerWallet $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, CustomerWallet $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, CustomerWallet $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['customer-wallets.update', 'customer_wallets.update', 'customerWallet.update', 'customer-wallet.update', 'edit customer wallets', 'update customer wallets']);
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