<?php

namespace App\Policies;

use App\Models\PosTerminal;
use App\Models\User;

class PosTerminalPolicy
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
        return $this->canAny($user, ['pos-terminals.view', 'pos_terminals.view', 'posTerminal.view', 'pos-terminal.view', 'view pos terminals']);
    }

    public function view(User $user, PosTerminal $record): bool
    {
        return $this->canAny($user, ['pos-terminals.view', 'pos_terminals.view', 'posTerminal.view', 'pos-terminal.view', 'view pos terminals']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['pos-terminals.create', 'pos_terminals.create', 'posTerminal.create', 'pos-terminal.create', 'create pos terminals']);
    }

    public function update(User $user, PosTerminal $record): bool
    {
        return $this->canAny($user, ['pos-terminals.update', 'pos_terminals.update', 'posTerminal.update', 'pos-terminal.update', 'edit pos terminals', 'update pos terminals']);
    }

    public function delete(User $user, PosTerminal $record): bool
    {
        return $this->canAny($user, ['pos-terminals.delete', 'pos_terminals.delete', 'posTerminal.delete', 'pos-terminal.delete', 'delete pos terminals']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['pos-terminals.delete', 'pos_terminals.delete', 'posTerminal.delete', 'pos-terminal.delete', 'delete pos terminals']);
    }

    public function forceDelete(User $user, PosTerminal $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, PosTerminal $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, PosTerminal $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['pos-terminals.update', 'pos_terminals.update', 'posTerminal.update', 'pos-terminal.update', 'edit pos terminals', 'update pos terminals']);
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