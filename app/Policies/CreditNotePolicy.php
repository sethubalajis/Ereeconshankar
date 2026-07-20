<?php

namespace App\Policies;

use App\Models\CreditNote;
use App\Models\User;

class CreditNotePolicy
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
        return $this->canAny($user, ['credit-notes.view', 'credit_notes.view', 'creditNote.view', 'credit-note.view', 'view credit notes']);
    }

    public function view(User $user, CreditNote $record): bool
    {
        return $this->canAny($user, ['credit-notes.view', 'credit_notes.view', 'creditNote.view', 'credit-note.view', 'view credit notes']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['credit-notes.create', 'credit_notes.create', 'creditNote.create', 'credit-note.create', 'create credit notes']);
    }

    public function update(User $user, CreditNote $record): bool
    {
        return $this->canAny($user, ['credit-notes.update', 'credit_notes.update', 'creditNote.update', 'credit-note.update', 'edit credit notes', 'update credit notes']);
    }

    public function delete(User $user, CreditNote $record): bool
    {
        return $this->canAny($user, ['credit-notes.delete', 'credit_notes.delete', 'creditNote.delete', 'credit-note.delete', 'delete credit notes']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['credit-notes.delete', 'credit_notes.delete', 'creditNote.delete', 'credit-note.delete', 'delete credit notes']);
    }

    public function forceDelete(User $user, CreditNote $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, CreditNote $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, CreditNote $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['credit-notes.update', 'credit_notes.update', 'creditNote.update', 'credit-note.update', 'edit credit notes', 'update credit notes']);
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