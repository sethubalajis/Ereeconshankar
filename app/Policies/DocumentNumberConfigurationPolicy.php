<?php

namespace App\Policies;

use App\Models\DocumentNumberConfiguration;
use App\Models\User;

class DocumentNumberConfigurationPolicy
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
        return $this->canAny($user, ['document_number_configurations.view', 'view document number configurations']);
    }

    public function view(User $user, DocumentNumberConfiguration $model): bool
    {
        return $this->canAny($user, ['document_number_configurations.view', 'view document number configurations']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['document_number_configurations.create', 'create document number configurations']);
    }

    public function update(User $user, DocumentNumberConfiguration $model): bool
    {
        return $this->canAny($user, ['document_number_configurations.update', 'edit document number configurations']);
    }

    public function delete(User $user, DocumentNumberConfiguration $model): bool
    {
        return $this->canAny($user, ['document_number_configurations.delete', 'delete document number configurations']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['document_number_configurations.delete', 'delete document number configurations']);
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