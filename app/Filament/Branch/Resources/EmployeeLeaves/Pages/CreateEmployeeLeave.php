<?php

namespace App\Filament\Branch\Resources\EmployeeLeaves\Pages;

use App\Filament\Branch\Resources\EmployeeLeaves\EmployeeLeaveResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployeeLeave extends CreateRecord
{
    protected static string $resource = EmployeeLeaveResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
