<?php

namespace App\Filament\Resources\Roles\Pages;

use App\Filament\Resources\Roles\RoleResource;
use App\Models\Permission;
use App\Models\Role;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Str;

class RolePermissions extends Page
{
    protected static string $resource = RoleResource::class;

    protected string $view = 'filament.resources.roles.pages.role-permissions';

    public Role $record;

    public array $matrix = [];

    public array $modules = [];

    public array $availablePermissionsByAction = [];

    protected const ACTION_ALIASES = [
        'view' => ['view', 'viewAny'],
        'create' => ['create'],
        'update' => ['update', 'edit'],
        'delete' => ['delete', 'deleteAny'],
    ];

    protected const ACTION_LABELS = [
        'view' => 'View',
        'create' => 'Create',
        'update' => 'Edit',
        'delete' => 'Delete',
    ];

    public function mount(Role $record): void
    {
        $this->record = $record;
        $grantedPermissions = array_flip($record->permissions()->pluck('name')->all());

        foreach (Permission::query()->orderBy('name')->get() as $permission) {
            $parts = explode('.', $permission->name, 2);

            if (count($parts) !== 2) {
                continue;
            }

            [$module, $action] = $parts;
            $normalizedAction = $this->normalizeAction($action);

            if ($normalizedAction === null) {
                continue;
            }

            $this->modules[$module] = $this->modules[$module] ?? Str::headline(str_replace(['-', '_'], ' ', $module));
            $this->availablePermissionsByAction[$module][$normalizedAction][] = $permission->name;
        }

        ksort($this->modules);

        foreach (array_keys($this->modules) as $module) {
            foreach (array_keys(self::ACTION_LABELS) as $action) {
                $permissionNames = $this->availablePermissionsByAction[$module][$action] ?? [];

                $this->matrix[$module][$action] = $permissionNames !== []
                    && collect($permissionNames)->contains(fn (string $permissionName): bool => isset($grantedPermissions[$permissionName]));
            }
        }
    }

    public function getActionLabels(): array
    {
        return self::ACTION_LABELS;
    }

    public function hasAction(string $module, string $action): bool
    {
        return ($this->availablePermissionsByAction[$module][$action] ?? []) !== [];
    }

    public function save(): void
    {
        $permissions = [];

        foreach ($this->matrix as $module => $actions) {
            foreach ($actions as $action => $enabled) {
                if (! $enabled) {
                    continue;
                }

                foreach ($this->availablePermissionsByAction[$module][$action] ?? [] as $permissionName) {
                    $permissions[] = $permissionName;
                }
            }
        }

        $this->record->syncPermissions(array_values(array_unique($permissions)));

        Notification::make()
            ->title('Permissions updated')
            ->success()
            ->send();
    }

    protected function normalizeAction(string $action): ?string
    {
        foreach (self::ACTION_ALIASES as $normalizedAction => $aliases) {
            if (in_array($action, $aliases, true)) {
                return $normalizedAction;
            }
        }

        return null;
    }
}