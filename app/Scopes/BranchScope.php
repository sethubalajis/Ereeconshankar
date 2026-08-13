<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class BranchScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if (auth()->check() && !auth()->user()->hasRole(['Super Admin', 'super admin'])) {
            $branchIds = auth()->user()->branches->pluck('id')->toArray();
            if (!empty($branchIds)) {
                $builder->whereIn($model->getTable() . '.branch_id', $branchIds);
            } else {
                $builder->whereRaw('1 = 0'); // Deny access if no branch assigned
            }
        }
    }
}