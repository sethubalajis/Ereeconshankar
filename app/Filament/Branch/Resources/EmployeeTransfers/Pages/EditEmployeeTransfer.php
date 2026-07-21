<?php

namespace App\Filament\Branch\Resources\EmployeeTransfers\Pages;

use App\Filament\Branch\Resources\EmployeeTransfers\EmployeeTransferResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEmployeeTransfer extends EditRecord
{
    protected static string $resource = EmployeeTransferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
