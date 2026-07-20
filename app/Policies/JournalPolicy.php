<?php

namespace App\Policies;

use App\Models\Journal;
use App\Models\User;

class JournalPolicy
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
        return $this->canAny($user, ['journals.view', 'journals.view', 'journal.view', 'journal.view', 'view journals']);
    }

    public function view(User $user, Journal $record): bool
    {
        return $this->canAny($user, ['journals.view', 'journals.view', 'journal.view', 'journal.view', 'view journals']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['journals.create', 'journals.create', 'journal.create', 'journal.create', 'create journals']);
    }

    public function update(User $user, Journal $record): bool
    {
        return $this->canAny($user, ['journals.update', 'journals.update', 'journal.update', 'journal.update', 'edit journals', 'update journals']);
    }

    public function delete(User $user, Journal $record): bool
    {
        return $this->canAny($user, ['journals.delete', 'journals.delete', 'journal.delete', 'journal.delete', 'delete journals']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['journals.delete', 'journals.delete', 'journal.delete', 'journal.delete', 'delete journals']);
    }

    public function forceDelete(User $user, Journal $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, Journal $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, Journal $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['journals.update', 'journals.update', 'journal.update', 'journal.update', 'edit journals', 'update journals']);
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