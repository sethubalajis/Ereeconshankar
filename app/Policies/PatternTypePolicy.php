<?php

namespace App\Policies;

use App\Models\PatternType;
use App\Models\User;

class PatternTypePolicy
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
        return $this->canAny($user, ['pattern-types.view', 'pattern_types.view', 'patternType.view', 'pattern-type.view', 'view pattern types']);
    }

    public function view(User $user, PatternType $record): bool
    {
        return $this->canAny($user, ['pattern-types.view', 'pattern_types.view', 'patternType.view', 'pattern-type.view', 'view pattern types']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['pattern-types.create', 'pattern_types.create', 'patternType.create', 'pattern-type.create', 'create pattern types']);
    }

    public function update(User $user, PatternType $record): bool
    {
        return $this->canAny($user, ['pattern-types.update', 'pattern_types.update', 'patternType.update', 'pattern-type.update', 'edit pattern types', 'update pattern types']);
    }

    public function delete(User $user, PatternType $record): bool
    {
        return $this->canAny($user, ['pattern-types.delete', 'pattern_types.delete', 'patternType.delete', 'pattern-type.delete', 'delete pattern types']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['pattern-types.delete', 'pattern_types.delete', 'patternType.delete', 'pattern-type.delete', 'delete pattern types']);
    }

    public function forceDelete(User $user, PatternType $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, PatternType $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, PatternType $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['pattern-types.update', 'pattern_types.update', 'patternType.update', 'pattern-type.update', 'edit pattern types', 'update pattern types']);
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