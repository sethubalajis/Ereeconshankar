<?php

namespace App\Filament\Branch\Resources\TransferRequests\Pages;

use App\Filament\Branch\Resources\TransferRequests\TransferRequestResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTransferRequest extends CreateRecord
{
    protected static string $resource = TransferRequestResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
