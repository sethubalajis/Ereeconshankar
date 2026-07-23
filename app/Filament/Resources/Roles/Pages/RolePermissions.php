<?php

namespace App\Filament\Resources\Roles\Pages;

use App\Filament\Resources\Roles\RoleResource;
use App\Models\Branch;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class RolePermissions extends Page
{
    protected static string $resource = RoleResource::class;

    protected string $view = 'filament.resources.roles.pages.role-permissions';

    public Role $record;

    public array $matrix = [];

    public array $modules = [];

    public array $availablePermissionsByAction = [];

    public array $branchSpecificPermissions = [];

    public array $branchPermissionMatrices = [];

    public ?int $selectedBranchId = null;

    public string $matrixRenderKey = '';

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
        $this->selectedBranchId = $record->branch_id ?? null;
        $this->matrixRenderKey = 'branch-' . ($this->selectedBranchId ?? 'none');
        $this->loadPermissionsMatrix();
    }

    public function updatedSelectedBranchId($value, $oldValue): void
    {
        $this->persistBranchMatrix($oldValue);

        $this->matrixRenderKey = 'branch-' . ($value ?? 'none');
        $this->loadPermissionsMatrix();
        $this->dispatch('$refresh');
    }

    public function refreshMatrix(): void
    {
        $this->persistBranchMatrix($this->selectedBranchId);

        $this->matrixRenderKey = 'branch-' . ($this->selectedBranchId ?? 'none');
        $this->loadPermissionsMatrix();
        $this->dispatch('$refresh');
    }

    protected function loadPermissionsMatrix(): void
    {
        $this->modules = [];
        $this->availablePermissionsByAction = [];
        $this->matrix = [];
        $this->branchSpecificPermissions = [];

        $branchId = $this->selectedBranchId;

        $grantedPermissions = [];
        $baseRolePermissions = Permission::query()
            ->join('role_permissions', 'role_permissions.permission_id', '=', 'permissions.id')
            ->where('role_permissions.role_id', $this->record->getKey());

        if (schemaHasColumn('role_permissions', 'branch_id')) {
            $baseRolePermissions = $baseRolePermissions->where(function ($query) use ($branchId): void {
                $query->whereNull('role_permissions.branch_id');
                if ($branchId !== null && $branchId !== '') {
                    $query->orWhere('role_permissions.branch_id', $branchId);
                }
            });
        }

        $grantedPermissions = array_flip($baseRolePermissions->pluck('permissions.name')->all());

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

                $defaultChecked = $permissionNames !== []
                    && collect($permissionNames)->contains(fn (string $permissionName): bool => isset($grantedPermissions[$permissionName]));

                $this->matrix[$module][$action] = $defaultChecked;
                $this->branchSpecificPermissions[$module][$action] = $defaultChecked;
            }
        }

        if ($this->selectedBranchId !== null && $this->selectedBranchId !== '' && schemaHasColumn('role_permissions', 'branch_id')) {
            $branchPermissions = RolePermission::query()
                ->where('role_id', $this->record->getKey())
                ->where('branch_id', $this->selectedBranchId)
                ->pluck('permission_id')
                ->all();

            if ($branchPermissions !== []) {
                $branchPermissionNames = Permission::query()
                    ->whereIn('id', $branchPermissions)
                    ->pluck('name')
                    ->all();

                foreach (array_keys($this->modules) as $module) {
                    foreach (array_keys(self::ACTION_LABELS) as $action) {
                        $permissionNames = $this->availablePermissionsByAction[$module][$action] ?? [];
                        $checked = collect($permissionNames)->contains(fn (string $permissionName): bool => in_array($permissionName, $branchPermissionNames, true));
                        $this->matrix[$module][$action] = $checked;
                        $this->branchSpecificPermissions[$module][$action] = $checked;
                    }
                }
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
        if ($this->selectedBranchId) {
            $this->persistBranchMatrix($this->selectedBranchId);

            foreach (User::query()->whereHas('roles', fn ($query) => $query->where('roles.id', $this->record->id))->get() as $user) {
                $user->grantBranchAccess($this->selectedBranchId);
            }
        }

        $permissions = [];
        $guardName = $this->record->guard_name ?? 'web';

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

        $permissionIds = Permission::query()
            ->whereIn('name', array_values(array_unique($permissions)))
            ->where('guard_name', $guardName)
            ->pluck('id')
            ->all();

        if ($this->selectedBranchId !== null && $this->selectedBranchId !== '' && schemaHasColumn('role_permissions', 'branch_id')) {
            $existingRows = RolePermission::query()
                ->where('role_id', $this->record->getKey())
                ->where('branch_id', $this->selectedBranchId)
                ->get();

            $existingPermissionIds = $existingRows->pluck('permission_id')->all();
            $newPermissionIds = array_values(array_unique($permissionIds));

            foreach ($existingRows as $row) {
                if (! in_array($row->permission_id, $newPermissionIds, true)) {
                    $row->delete();
                }
            }

            foreach ($newPermissionIds as $permissionId) {
                RolePermission::query()->updateOrCreate(
                    [
                        'role_id' => $this->record->getKey(),
                        'permission_id' => $permissionId,
                        'branch_id' => $this->selectedBranchId,
                    ],
                    [
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
        } else {
            $this->record->permissions()->sync($permissionIds);
        }

        $this->record->forgetCachedPermissions();

        Notification::make()
            ->title('Permissions updated')
            ->success()
            ->send();
    }

    public function getBranchOptions(): array
    {
        return Branch::query()
            ->orderBy('name')
            ->get()
            ->mapWithKeys(fn (Branch $branch): array => [$branch->id => $branch->name])
            ->toArray();
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

    protected function persistBranchMatrix($branchId): void
    {
        if ($branchId === null || $branchId === '' || ! isset($this->matrix)) {
            return;
        }

        $this->branchPermissionMatrices[(int) $branchId] = $this->matrix;
        session()->put($this->getBranchMatrixStorageKey($branchId), $this->matrix);
    }

    protected function restoreBranchMatrix(int $branchId): ?array
    {
        $stored = session()->get($this->getBranchMatrixStorageKey($branchId));

        if (is_array($stored)) {
            return $stored;
        }

        return $this->branchPermissionMatrices[$branchId] ?? null;
    }

    protected function getBranchMatrixStorageKey(int $branchId): string
    {
        return 'role-permissions.' . $this->record->getKey() . '.' . $branchId;
    }
}

function schemaHasColumn(string $table, string $column): bool
{
    return Schema::hasColumn($table, $column);
}