<?php

namespace App\Filament\Branch\Resources\EmployeeExits\Pages;

use App\Filament\Branch\Resources\EmployeeExits\EmployeeExitResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployeeExit extends CreateRecord
{
    protected static string $resource = EmployeeExitResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
