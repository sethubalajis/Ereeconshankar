<?php

namespace App\Filament\Branch\Resources\EmployeeExits\Pages;

use App\Filament\Branch\Resources\EmployeeExits\EmployeeExitResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEmployeeExits extends ListRecords
{
    protected static string $resource = EmployeeExitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
