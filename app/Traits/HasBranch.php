<?php

namespace App\Traits;

use App\Scopes\BranchScope;

trait HasBranch
{
    protected static function bootHasBranch()
    {
        static::addGlobalScope(new BranchScope);
    }
}