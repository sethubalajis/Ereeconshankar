<?php

namespace App\Policies;

use App\Models\DashboardWidget;
use App\Models\User;

class DashboardWidgetPolicy
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
        return $this->canAny($user, ['dashboard-widgets.view', 'dashboard_widgets.view', 'dashboardWidget.view', 'dashboard-widget.view', 'view dashboard widgets']);
    }

    public function view(User $user, DashboardWidget $record): bool
    {
        return $this->canAny($user, ['dashboard-widgets.view', 'dashboard_widgets.view', 'dashboardWidget.view', 'dashboard-widget.view', 'view dashboard widgets']);
    }

    public function create(User $user): bool
    {
        return $this->canAny($user, ['dashboard-widgets.create', 'dashboard_widgets.create', 'dashboardWidget.create', 'dashboard-widget.create', 'create dashboard widgets']);
    }

    public function update(User $user, DashboardWidget $record): bool
    {
        return $this->canAny($user, ['dashboard-widgets.update', 'dashboard_widgets.update', 'dashboardWidget.update', 'dashboard-widget.update', 'edit dashboard widgets', 'update dashboard widgets']);
    }

    public function delete(User $user, DashboardWidget $record): bool
    {
        return $this->canAny($user, ['dashboard-widgets.delete', 'dashboard_widgets.delete', 'dashboardWidget.delete', 'dashboard-widget.delete', 'delete dashboard widgets']);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canAny($user, ['dashboard-widgets.delete', 'dashboard_widgets.delete', 'dashboardWidget.delete', 'dashboard-widget.delete', 'delete dashboard widgets']);
    }

    public function forceDelete(User $user, DashboardWidget $record): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, DashboardWidget $record): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, DashboardWidget $record): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $this->canAny($user, ['dashboard-widgets.update', 'dashboard_widgets.update', 'dashboardWidget.update', 'dashboard-widget.update', 'edit dashboard widgets', 'update dashboard widgets']);
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