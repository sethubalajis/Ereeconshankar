<?php

namespace App\Policies;

use App\Models\FabricType;
use App\Models\User;

class FabricTypePolicy
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
        return $this->canAny($user, ['fabric-types.view', 'fabric_types.view', 'fabricType.view', 'fabric-type.view', 'view fabric types']);
    }

    public function view(User $user, FabricType $record): bool
    {
        return $this->canAny($user, ['fabric-types.view', 'fabric_types.view', 'fabricType.view', 'fabric-type.view', 'view fabric types']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['fabric-types.create', 'fabric_types.create', 'fabricType.create', 'fabric-type.create', 'create fabric types']);
    }

    public function update(User $user, FabricType $record): bool
    {
        return $this->canAny($user, ['fabric-types.update', 'fabric_types.update', 'fabricType.update', 'fabric-type.update', 'edit fabric types', 'update fabric types']);
    }

    public function delete(User $user, FabricType $record): bool
    {
        return $this->canAny($user, ['fabric-types.delete', 'fabric_types.delete', 'fabricType.delete', 'fabric-type.delete', 'delete fabric types']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['fabric-types.delete', 'fabric_types.delete', 'fabricType.delete', 'fabric-type.delete', 'delete fabric types']);
    }

    public function forceDelete(User $user, FabricType $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, FabricType $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, FabricType $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['fabric-types.update', 'fabric_types.update', 'fabricType.update', 'fabric-type.update', 'edit fabric types', 'update fabric types']);
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