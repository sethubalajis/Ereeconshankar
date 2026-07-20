<?php

namespace App\Policies;

use App\Models\ComplaintTicket;
use App\Models\User;

class ComplaintTicketPolicy
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
        return $this->canAny($user, ['complaint-tickets.view', 'complaint_tickets.view', 'complaintTicket.view', 'complaint-ticket.view', 'view complaint tickets']);
    }

    public function view(User $user, ComplaintTicket $record): bool
    {
        return $this->canAny($user, ['complaint-tickets.view', 'complaint_tickets.view', 'complaintTicket.view', 'complaint-ticket.view', 'view complaint tickets']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['complaint-tickets.create', 'complaint_tickets.create', 'complaintTicket.create', 'complaint-ticket.create', 'create complaint tickets']);
    }

    public function update(User $user, ComplaintTicket $record): bool
    {
        return $this->canAny($user, ['complaint-tickets.update', 'complaint_tickets.update', 'complaintTicket.update', 'complaint-ticket.update', 'edit complaint tickets', 'update complaint tickets']);
    }

    public function delete(User $user, ComplaintTicket $record): bool
    {
        return $this->canAny($user, ['complaint-tickets.delete', 'complaint_tickets.delete', 'complaintTicket.delete', 'complaint-ticket.delete', 'delete complaint tickets']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['complaint-tickets.delete', 'complaint_tickets.delete', 'complaintTicket.delete', 'complaint-ticket.delete', 'delete complaint tickets']);
    }

    public function forceDelete(User $user, ComplaintTicket $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, ComplaintTicket $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, ComplaintTicket $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['complaint-tickets.update', 'complaint_tickets.update', 'complaintTicket.update', 'complaint-ticket.update', 'edit complaint tickets', 'update complaint tickets']);
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