<?php

namespace App\Policies;

use App\Models\GstCategory;
use App\Models\User;

class GstCategoryPolicy
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
        return $this->canAny($user, ['gst-categories.view', 'gst_categories.view', 'gstCategory.view', 'gst-category.view', 'view gst categories']);
    }

    public function view(User $user, GstCategory $record): bool
    {
        return $this->canAny($user, ['gst-categories.view', 'gst_categories.view', 'gstCategory.view', 'gst-category.view', 'view gst categories']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['gst-categories.create', 'gst_categories.create', 'gstCategory.create', 'gst-category.create', 'create gst categories']);
    }

    public function update(User $user, GstCategory $record): bool
    {
        return $this->canAny($user, ['gst-categories.update', 'gst_categories.update', 'gstCategory.update', 'gst-category.update', 'edit gst categories', 'update gst categories']);
    }

    public function delete(User $user, GstCategory $record): bool
    {
        return $this->canAny($user, ['gst-categories.delete', 'gst_categories.delete', 'gstCategory.delete', 'gst-category.delete', 'delete gst categories']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['gst-categories.delete', 'gst_categories.delete', 'gstCategory.delete', 'gst-category.delete', 'delete gst categories']);
    }

    public function forceDelete(User $user, GstCategory $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, GstCategory $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, GstCategory $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['gst-categories.update', 'gst_categories.update', 'gstCategory.update', 'gst-category.update', 'edit gst categories', 'update gst categories']);
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