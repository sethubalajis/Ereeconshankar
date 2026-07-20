<?php

namespace App\Policies;

use App\Models\BranchSetting;
use App\Models\User;

class BranchSettingPolicy
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
        return $this->canAny($user, ['branch-settings.view', 'branch_settings.view', 'branchSetting.view', 'branch-setting.view', 'view branch settings']);
    }

    public function view(User $user, BranchSetting $record): bool
    {
        return $this->canAny($user, ['branch-settings.view', 'branch_settings.view', 'branchSetting.view', 'branch-setting.view', 'view branch settings']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['branch-settings.create', 'branch_settings.create', 'branchSetting.create', 'branch-setting.create', 'create branch settings']);
    }

    public function update(User $user, BranchSetting $record): bool
    {
        return $this->canAny($user, ['branch-settings.update', 'branch_settings.update', 'branchSetting.update', 'branch-setting.update', 'edit branch settings', 'update branch settings']);
    }

    public function delete(User $user, BranchSetting $record): bool
    {
        return $this->canAny($user, ['branch-settings.delete', 'branch_settings.delete', 'branchSetting.delete', 'branch-setting.delete', 'delete branch settings']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['branch-settings.delete', 'branch_settings.delete', 'branchSetting.delete', 'branch-setting.delete', 'delete branch settings']);
    }

    public function forceDelete(User $user, BranchSetting $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, BranchSetting $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, BranchSetting $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['branch-settings.update', 'branch_settings.update', 'branchSetting.update', 'branch-setting.update', 'edit branch settings', 'update branch settings']);
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