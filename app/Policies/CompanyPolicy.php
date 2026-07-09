<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;

class CompanyPolicy
{
    /**
     * Super admins can do everything.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->hasRole('Super Admin') || $user->hasRole('Admin')) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $this->canAny($user, [
            'companies.view',
            'company.view',
            'view companies',
        ]);
    }

    public function view(User $user, Company $company): bool
    {
        return $this->canAny($user, [
            'companies.view',
            'company.view',
            'view companies',
        ]);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, [
            'companies.create',
            'company.create',
            'create companies',
        ]);
    }

    public function update(User $user, Company $company): bool
    {
        return $this->canAny($user, [
            'companies.update',
            'company.update',
            'edit companies',
            'update companies',
        ]);
    }

    public function delete(User $user, Company $company): bool
    {
        return $this->canAny($user, [
            'companies.delete',
            'company.delete',
            'delete companies',
        ]);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, [
            'companies.delete',
            'company.delete',
            'delete companies',
        ]);
    }

    public function forceDelete(User $user, Company $company): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, Company $company): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, Company $company): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, [
            'companies.update',
            'company.update',
            'edit companies',
            'update companies',
        ]);
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
