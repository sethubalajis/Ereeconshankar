<?php

namespace App\Filament\Branch\Resources\BranchSettings\Pages;

use App\Filament\Branch\Resources\BranchSettings\BranchSettingResource;
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
