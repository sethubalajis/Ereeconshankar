<?php

namespace App\Policies;

use App\Models\AttributeValue;
use App\Models\User;

class AttributeValuePolicy
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
        return $this->canAny($user, ['attribute-values.view', 'attribute_values.view', 'attributeValue.view', 'attribute-value.view', 'view attribute values']);
    }

    public function view(User $user, AttributeValue $attributeValue): bool
    {
        return $this->canAny($user, ['attribute-values.view', 'attribute_values.view', 'attributeValue.view', 'attribute-value.view', 'view attribute values']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['attribute-values.create', 'attribute_values.create', 'attributeValue.create', 'attribute-value.create', 'create attribute values']);
    }

    public function update(User $user, AttributeValue $attributeValue): bool
    {
        return $this->canAny($user, ['attribute-values.update', 'attribute_values.update', 'attributeValue.update', 'attribute-value.update', 'edit attribute values', 'update attribute values']);
    }

    public function delete(User $user, AttributeValue $attributeValue): bool
    {
        return $this->canAny($user, ['attribute-values.delete', 'attribute_values.delete', 'attributeValue.delete', 'attribute-value.delete', 'delete attribute values']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['attribute-values.delete', 'attribute_values.delete', 'attributeValue.delete', 'attribute-value.delete', 'delete attribute values']);
    }

    public function forceDelete(User $user, AttributeValue $attributeValue): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, AttributeValue $attributeValue): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, AttributeValue $attributeValue): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['attribute-values.update', 'attribute_values.update', 'attributeValue.update', 'attribute-value.update', 'edit attribute values', 'update attribute values']);
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
