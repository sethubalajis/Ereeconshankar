<?php

namespace App\Policies;

use App\Models\ComplaintCategory;
use App\Models\User;

class ComplaintCategoryPolicy
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
        return $this->canAny($user, ['complaint-categories.view', 'complaint_categories.view', 'complaintCategory.view', 'complaint-category.view', 'view complaint categories']);
    }

    public function view(User $user, ComplaintCategory $record): bool
    {
        return $this->canAny($user, ['complaint-categories.view', 'complaint_categories.view', 'complaintCategory.view', 'complaint-category.view', 'view complaint categories']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['complaint-categories.create', 'complaint_categories.create', 'complaintCategory.create', 'complaint-category.create', 'create complaint categories']);
    }

    public function update(User $user, ComplaintCategory $record): bool
    {
        return $this->canAny($user, ['complaint-categories.update', 'complaint_categories.update', 'complaintCategory.update', 'complaint-category.update', 'edit complaint categories', 'update complaint categories']);
    }

    public function delete(User $user, ComplaintCategory $record): bool
    {
        return $this->canAny($user, ['complaint-categories.delete', 'complaint_categories.delete', 'complaintCategory.delete', 'complaint-category.delete', 'delete complaint categories']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['complaint-categories.delete', 'complaint_categories.delete', 'complaintCategory.delete', 'complaint-category.delete', 'delete complaint categories']);
    }

    public function forceDelete(User $user, ComplaintCategory $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, ComplaintCategory $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, ComplaintCategory $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['complaint-categories.update', 'complaint_categories.update', 'complaintCategory.update', 'complaint-category.update', 'edit complaint categories', 'update complaint categories']);
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