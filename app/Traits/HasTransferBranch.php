<?php

namespace App\Traits;

use App\Scopes\TransferBranchScope;

trait HasTransferBranch
{
    protected static function bootHasTransferBranch()
    {
        static::addGlobalScope(new TransferBranchScope);
    }
}