<?php

namespace App\Filament\Branch\Resources\TransferItems\Pages;

use App\Filament\Branch\Resources\TransferItems\TransferItemResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTransferItem extends CreateRecord
{
    protected static string $resource = TransferItemResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
