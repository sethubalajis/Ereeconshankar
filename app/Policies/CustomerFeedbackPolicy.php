<?php

namespace App\Policies;

use App\Models\CustomerFeedback;
use App\Models\User;

class CustomerFeedbackPolicy
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
        return $this->canAny($user, ['customer-feedback.view', 'customer_feedback.view', 'customerFeedback.view', 'customer-feedback.view', 'view customer feedback']);
    }

    public function view(User $user, CustomerFeedback $record): bool
    {
        return $this->canAny($user, ['customer-feedback.view', 'customer_feedback.view', 'customerFeedback.view', 'customer-feedback.view', 'view customer feedback']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['customer-feedback.create', 'customer_feedback.create', 'customerFeedback.create', 'customer-feedback.create', 'create customer feedback']);
    }

    public function update(User $user, CustomerFeedback $record): bool
    {
        return $this->canAny($user, ['customer-feedback.update', 'customer_feedback.update', 'customerFeedback.update', 'customer-feedback.update', 'edit customer feedback', 'update customer feedback']);
    }

    public function delete(User $user, CustomerFeedback $record): bool
    {
        return $this->canAny($user, ['customer-feedback.delete', 'customer_feedback.delete', 'customerFeedback.delete', 'customer-feedback.delete', 'delete customer feedback']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['customer-feedback.delete', 'customer_feedback.delete', 'customerFeedback.delete', 'customer-feedback.delete', 'delete customer feedback']);
    }

    public function forceDelete(User $user, CustomerFeedback $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, CustomerFeedback $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, CustomerFeedback $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['customer-feedback.update', 'customer_feedback.update', 'customerFeedback.update', 'customer-feedback.update', 'edit customer feedback', 'update customer feedback']);
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