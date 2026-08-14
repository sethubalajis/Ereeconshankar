<?php

namespace App\Filament\Branch\Resources\TransferReceipts\Pages;

use App\Filament\Branch\Resources\TransferReceipts\TransferReceiptResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTransferReceipt extends CreateRecord
{
    protected static string $resource = TransferReceiptResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
