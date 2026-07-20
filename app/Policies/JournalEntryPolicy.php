<?php

namespace App\Policies;

use App\Models\JournalEntry;
use App\Models\User;

class JournalEntryPolicy
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
        return $this->canAny($user, ['journal-entries.view', 'journal_entries.view', 'journalEntry.view', 'journal-entry.view', 'view journal entries']);
    }

    public function view(User $user, JournalEntry $record): bool
    {
        return $this->canAny($user, ['journal-entries.view', 'journal_entries.view', 'journalEntry.view', 'journal-entry.view', 'view journal entries']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['journal-entries.create', 'journal_entries.create', 'journalEntry.create', 'journal-entry.create', 'create journal entries']);
    }

    public function update(User $user, JournalEntry $record): bool
    {
        return $this->canAny($user, ['journal-entries.update', 'journal_entries.update', 'journalEntry.update', 'journal-entry.update', 'edit journal entries', 'update journal entries']);
    }

    public function delete(User $user, JournalEntry $record): bool
    {
        return $this->canAny($user, ['journal-entries.delete', 'journal_entries.delete', 'journalEntry.delete', 'journal-entry.delete', 'delete journal entries']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['journal-entries.delete', 'journal_entries.delete', 'journalEntry.delete', 'journal-entry.delete', 'delete journal entries']);
    }

    public function forceDelete(User $user, JournalEntry $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, JournalEntry $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, JournalEntry $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['journal-entries.update', 'journal_entries.update', 'journalEntry.update', 'journal-entry.update', 'edit journal entries', 'update journal entries']);
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