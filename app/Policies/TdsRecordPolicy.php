<?php

namespace App\Policies;

use App\Models\TdsRecord;
use App\Models\User;

class TdsRecordPolicy
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
        return $this->canAny($user, ['tds-records.view', 'tds_records.view', 'tdsRecord.view', 'tds-record.view', 'view tds records']);
    }

    public function view(User $user, TdsRecord $record): bool
    {
        return $this->canAny($user, ['tds-records.view', 'tds_records.view', 'tdsRecord.view', 'tds-record.view', 'view tds records']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['tds-records.create', 'tds_records.create', 'tdsRecord.create', 'tds-record.create', 'create tds records']);
    }

    public function update(User $user, TdsRecord $record): bool
    {
        return $this->canAny($user, ['tds-records.update', 'tds_records.update', 'tdsRecord.update', 'tds-record.update', 'edit tds records', 'update tds records']);
    }

    public function delete(User $user, TdsRecord $record): bool
    {
        return $this->canAny($user, ['tds-records.delete', 'tds_records.delete', 'tdsRecord.delete', 'tds-record.delete', 'delete tds records']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['tds-records.delete', 'tds_records.delete', 'tdsRecord.delete', 'tds-record.delete', 'delete tds records']);
    }

    public function forceDelete(User $user, TdsRecord $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, TdsRecord $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, TdsRecord $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['tds-records.update', 'tds_records.update', 'tdsRecord.update', 'tds-record.update', 'edit tds records', 'update tds records']);
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