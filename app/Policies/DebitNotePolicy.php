<?php

namespace App\Policies;

use App\Models\DebitNote;
use App\Models\User;

class DebitNotePolicy
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
        return $this->canAny($user, ['debit-notes.view', 'debit_notes.view', 'debitNote.view', 'debit-note.view', 'view debit notes']);
    }

    public function view(User $user, DebitNote $record): bool
    {
        return $this->canAny($user, ['debit-notes.view', 'debit_notes.view', 'debitNote.view', 'debit-note.view', 'view debit notes']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['debit-notes.create', 'debit_notes.create', 'debitNote.create', 'debit-note.create', 'create debit notes']);
    }

    public function update(User $user, DebitNote $record): bool
    {
        return $this->canAny($user, ['debit-notes.update', 'debit_notes.update', 'debitNote.update', 'debit-note.update', 'edit debit notes', 'update debit notes']);
    }

    public function delete(User $user, DebitNote $record): bool
    {
        return $this->canAny($user, ['debit-notes.delete', 'debit_notes.delete', 'debitNote.delete', 'debit-note.delete', 'delete debit notes']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['debit-notes.delete', 'debit_notes.delete', 'debitNote.delete', 'debit-note.delete', 'delete debit notes']);
    }

    public function forceDelete(User $user, DebitNote $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, DebitNote $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, DebitNote $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['debit-notes.update', 'debit_notes.update', 'debitNote.update', 'debit-note.update', 'edit debit notes', 'update debit notes']);
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