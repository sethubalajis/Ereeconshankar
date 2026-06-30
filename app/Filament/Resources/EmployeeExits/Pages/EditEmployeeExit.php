<?php

namespace App\Filament\Resources\EmployeeExits\Pages;

use App\Filament\Resources\EmployeeExits\EmployeeExitResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEmployeeExit extends EditRecord
{
    protected static string $resource = EmployeeExitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
