<?php

namespace App\Filament\Branch\Resources\EmployeeTransfers\Pages;

use App\Filament\Branch\Resources\EmployeeTransfers\EmployeeTransferResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployeeTransfer extends CreateRecord
{
    protected static string $resource = EmployeeTransferResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
