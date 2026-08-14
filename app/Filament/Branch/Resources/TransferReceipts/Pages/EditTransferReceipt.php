<?php

namespace App\Filament\Branch\Resources\TransferReceipts\Pages;

use App\Filament\Branch\Resources\TransferReceipts\TransferReceiptResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTransferReceipt extends EditRecord
{
    protected static string $resource = TransferReceiptResource::class;

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
