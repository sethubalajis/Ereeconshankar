<?php

namespace App\Filament\Branch\Resources;

use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

abstract class BaseBranchResource extends Resource
{
    protected static bool $isScopedToTenant = false;

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $modelClass = static::getModel();
        if (! $modelClass) {
            return $query;
        }

        $table = app($modelClass)->getTable();

        $tenantId = Filament::getTenant()?->getKey();

        if (! $tenantId && auth()->check()) {
            $tenantId = auth()->user()?->branches()->value('branches.id');
        }

        if (! $tenantId) {
            return $query->whereRaw('1 = 0');
        }

        if (Schema::hasColumn($table, 'branch_id')) {
            return $query->where($table . '.branch_id', $tenantId);
        }

        if (Schema::hasColumn($table, 'from_branch_id') && Schema::hasColumn($table, 'to_branch_id')) {
            return $query->where(function (Builder $builder) use ($table, $tenantId): void {
                $builder
                    ->where($table . '.from_branch_id', $tenantId)
                    ->orWhere($table . '.to_branch_id', $tenantId);
            });
        }

        if (Schema::hasColumn($table, 'from_branch_id')) {
            return $query->where($table . '.from_branch_id', $tenantId);
        }

        if (Schema::hasColumn($table, 'to_branch_id')) {
            return $query->where($table . '.to_branch_id', $tenantId);
        }

        return $query;
    }
}
