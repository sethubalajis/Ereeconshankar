<?php

namespace App\Filament\Branch\Resources\BranchTargets\Pages;

use App\Filament\Branch\Resources\BranchTargets\BranchTargetResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBranchTarget extends CreateRecord
{
    protected static string $resource = BranchTargetResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
