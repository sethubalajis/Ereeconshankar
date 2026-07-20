<?php

namespace App\Policies;

use App\Models\TcsRecord;
use App\Models\User;

class TcsRecordPolicy
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
        return $this->canAny($user, ['tcs-records.view', 'tcs_records.view', 'tcsRecord.view', 'tcs-record.view', 'view tcs records']);
    }

    public function view(User $user, TcsRecord $record): bool
    {
        return $this->canAny($user, ['tcs-records.view', 'tcs_records.view', 'tcsRecord.view', 'tcs-record.view', 'view tcs records']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['tcs-records.create', 'tcs_records.create', 'tcsRecord.create', 'tcs-record.create', 'create tcs records']);
    }

    public function update(User $user, TcsRecord $record): bool
    {
        return $this->canAny($user, ['tcs-records.update', 'tcs_records.update', 'tcsRecord.update', 'tcs-record.update', 'edit tcs records', 'update tcs records']);
    }

    public function delete(User $user, TcsRecord $record): bool
    {
        return $this->canAny($user, ['tcs-records.delete', 'tcs_records.delete', 'tcsRecord.delete', 'tcs-record.delete', 'delete tcs records']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['tcs-records.delete', 'tcs_records.delete', 'tcsRecord.delete', 'tcs-record.delete', 'delete tcs records']);
    }

    public function forceDelete(User $user, TcsRecord $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, TcsRecord $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, TcsRecord $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['tcs-records.update', 'tcs_records.update', 'tcsRecord.update', 'tcs-record.update', 'edit tcs records', 'update tcs records']);
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