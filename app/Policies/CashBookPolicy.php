<?php

namespace App\Policies;

use App\Models\CashBook;
use App\Models\User;

class CashBookPolicy
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
        return $this->canAny($user, ['cash-books.view', 'cash_books.view', 'cashBook.view', 'cash-book.view', 'view cash books']);
    }

    public function view(User $user, CashBook $record): bool
    {
        return $this->canAny($user, ['cash-books.view', 'cash_books.view', 'cashBook.view', 'cash-book.view', 'view cash books']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['cash-books.create', 'cash_books.create', 'cashBook.create', 'cash-book.create', 'create cash books']);
    }

    public function update(User $user, CashBook $record): bool
    {
        return $this->canAny($user, ['cash-books.update', 'cash_books.update', 'cashBook.update', 'cash-book.update', 'edit cash books', 'update cash books']);
    }

    public function delete(User $user, CashBook $record): bool
    {
        return $this->canAny($user, ['cash-books.delete', 'cash_books.delete', 'cashBook.delete', 'cash-book.delete', 'delete cash books']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['cash-books.delete', 'cash_books.delete', 'cashBook.delete', 'cash-book.delete', 'delete cash books']);
    }

    public function forceDelete(User $user, CashBook $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, CashBook $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, CashBook $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['cash-books.update', 'cash_books.update', 'cashBook.update', 'cash-book.update', 'edit cash books', 'update cash books']);
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