<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Models\Branch;
use App\Models\Permission;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class UserPermissions extends Page
{
    protected static string $resource = UserResource::class;

    protected string $view = 'filament.resources.users.pages.user-permissions';

    public User $record;

    public array $matrix = [];

    public array $modules = [];

    public array $availablePermissionsByAction = [];

    public array $inheritedPermissionsByAction = [];

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

    public function mount(User $record): void
    {
        $this->record = $record;
        $this->selectedBranchId = $this->resolveDefaultBranchId($record);
        $this->matrixRenderKey = 'branch-' . ($this->selectedBranchId ?? 'none');
        $this->loadPermissionsMatrix();
    }

    public function updatedSelectedBranchId($value, $oldValue): void
    {
        $this->persistBranchMatrix($oldValue);

        $branchId = $value !== null && $value !== '' ? (int) $value : null;
        if ($branchId !== null && ! $this->record->branches()->where('branches.id', $branchId)->exists()) {
            $branchId = $this->resolveDefaultBranchId($this->record);
        }

        $this->selectedBranchId = $branchId;
        Session::put('selected_branch_id', $branchId);
        $this->matrixRenderKey = 'branch-' . ($branchId ?? 'none');
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
        $this->inheritedPermissionsByAction = [];
        $this->matrix = [];

        $roleIds = $this->record->roles()->pluck('roles.id')->all();
        $branchId = $this->selectedBranchId;

        $baseRolePermissions = Permission::query()
            ->join('role_permissions', 'role_permissions.permission_id', '=', 'permissions.id')
            ->whereIn('role_permissions.role_id', $roleIds);

        if (Schema::hasColumn('role_permissions', 'branch_id')) {
            $baseRolePermissions = $baseRolePermissions->where(function ($query) use ($branchId): void {
                $query->whereNull('role_permissions.branch_id');
                if ($branchId !== null && $branchId !== '') {
                    $query->orWhere('role_permissions.branch_id', $branchId);
                }
            });
        }

        $permissionsViaRoles = $baseRolePermissions->pluck('permissions.name')->all();

        $directPermissions = array_flip($this->record->getDirectPermissions()->pluck('name')->all());

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

            if (in_array($permission->name, $permissionsViaRoles, true)) {
                $this->inheritedPermissionsByAction[$module][$normalizedAction] = true;
            }
        }

        ksort($this->modules);

        $branchState = null;
        if ($this->selectedBranchId !== null && $this->selectedBranchId !== '') {
            $branchState = $this->restoreBranchMatrix($this->selectedBranchId);
        }

        foreach (array_keys($this->modules) as $module) {
            foreach (array_keys(self::ACTION_LABELS) as $action) {
                $permissionNames = $this->availablePermissionsByAction[$module][$action] ?? [];

                $checked = $permissionNames !== []
                    && ! $this->isInheritedAction($module, $action)
                    && collect($permissionNames)->contains(fn (string $permissionName): bool => isset($directPermissions[$permissionName]));

                if ($branchState !== null && isset($branchState[$module][$action])) {
                    $checked = (bool) $branchState[$module][$action];
                }

                $this->matrix[$module][$action] = $checked;
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

    public function isInheritedAction(string $module, string $action): bool
    {
        return (bool) ($this->inheritedPermissionsByAction[$module][$action] ?? false);
    }

    public function save(): void
    {
        $this->persistBranchMatrix($this->selectedBranchId);

        $permissions = [];
        $guardName = $this->record->guard_name ?? 'web';

        foreach ($this->matrix as $module => $actions) {
            foreach ($actions as $action => $enabled) {
                if (! $enabled || $this->isInheritedAction($module, $action)) {
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

        $this->record->permissions()->sync($permissionIds);
        $this->record->forgetCachedPermissions();

        Notification::make()
            ->title('User permissions updated')
            ->success()
            ->send();
    }

    protected function resolveDefaultBranchId(User $record): ?int
    {
        $storedBranchId = Session::get('selected_branch_id');
        if ($storedBranchId !== null && $storedBranchId !== '' && $record->branches()->where('branches.id', (int) $storedBranchId)->exists()) {
            return (int) $storedBranchId;
        }

        $branch = $record->branches()->first();

        return $branch?->id;
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
        Session::put($this->getBranchMatrixStorageKey($branchId), $this->matrix);
    }

    protected function restoreBranchMatrix(int $branchId): ?array
    {
        $stored = Session::get($this->getBranchMatrixStorageKey($branchId));

        if (is_array($stored)) {
            return $stored;
        }

        return $this->branchPermissionMatrices[$branchId] ?? null;
    }

    protected function getBranchMatrixStorageKey(int $branchId): string
    {
        return 'user-permissions.' . $this->record->getKey() . '.' . $branchId;
    }

    public function getBranchOptions(): array
    {
        return Branch::query()
            ->orderBy('name')
            ->get()
            ->mapWithKeys(fn (Branch $branch): array => [$branch->id => $branch->name])
            ->toArray();
    }
}