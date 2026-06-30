<?php

namespace App\Filament\Resources\BranchSettings\Pages;

use App\Filament\Resources\BranchSettings\BranchSettingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBranchSettings extends ListRecords
{
    protected static string $resource = BranchSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
