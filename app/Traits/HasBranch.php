<?php

namespace App\Traits;

use App\Scopes\BranchScope;

trait HasBranch
{
    protected static function bootHasBranch()
    {
        static::addGlobalScope(new BranchScope);

        static::creating(function ($model) {
            if (empty($model->branch_id)) {
                $tenantId = null;
                try {
                    $tenantId = \Filament\Facades\Filament::getTenant()?->getKey();
                } catch (\Throwable $e) {}

                if (! $tenantId && auth()->check()) {
                    $tenantId = auth()->user()?->branches()->value('branches.id');
                }

                if ($tenantId) {
                    $model->branch_id = $tenantId;
                }
            }
        });
    }
}