<?php

namespace App\Filament\Branch\Resources\BranchTargets\Pages;

use App\Filament\Branch\Resources\BranchTargets\BranchTargetResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBranchTargets extends ListRecords
{
    protected static string $resource = BranchTargetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
