<?php

namespace App\Filament\Resources\UserBranchAccesses\Pages;

use App\Filament\Resources\UserBranchAccesses\UserBranchAccessResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUserBranchAccesses extends ListRecords
{
    protected static string $resource = UserBranchAccessResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
