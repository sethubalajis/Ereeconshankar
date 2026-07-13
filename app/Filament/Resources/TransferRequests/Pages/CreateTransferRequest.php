<?php

namespace App\Filament\Resources\TransferRequests\Pages;

use App\Filament\Resources\TransferRequests\TransferRequestResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTransferRequest extends CreateRecord
{
    protected static string $resource = TransferRequestResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
