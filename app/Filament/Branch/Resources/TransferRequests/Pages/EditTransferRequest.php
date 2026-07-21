<?php

namespace App\Filament\Branch\Resources\TransferRequests\Pages;

use App\Filament\Branch\Resources\TransferRequests\TransferRequestResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTransferRequest extends EditRecord
{
    protected static string $resource = TransferRequestResource::class;

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
