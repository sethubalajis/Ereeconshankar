<?php

namespace App\Policies;

use App\Models\ContraEntry;
use App\Models\User;

class ContraEntryPolicy
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
        return $this->canAny($user, ['contra-entries.view', 'contra_entries.view', 'contraEntry.view', 'contra-entry.view', 'view contra entries']);
    }

    public function view(User $user, ContraEntry $record): bool
    {
        return $this->canAny($user, ['contra-entries.view', 'contra_entries.view', 'contraEntry.view', 'contra-entry.view', 'view contra entries']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['contra-entries.create', 'contra_entries.create', 'contraEntry.create', 'contra-entry.create', 'create contra entries']);
    }

    public function update(User $user, ContraEntry $record): bool
    {
        return $this->canAny($user, ['contra-entries.update', 'contra_entries.update', 'contraEntry.update', 'contra-entry.update', 'edit contra entries', 'update contra entries']);
    }

    public function delete(User $user, ContraEntry $record): bool
    {
        return $this->canAny($user, ['contra-entries.delete', 'contra_entries.delete', 'contraEntry.delete', 'contra-entry.delete', 'delete contra entries']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['contra-entries.delete', 'contra_entries.delete', 'contraEntry.delete', 'contra-entry.delete', 'delete contra entries']);
    }

    public function forceDelete(User $user, ContraEntry $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, ContraEntry $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, ContraEntry $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['contra-entries.update', 'contra_entries.update', 'contraEntry.update', 'contra-entry.update', 'edit contra entries', 'update contra entries']);
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