<?php

namespace App\Filament\Resources\TransferReceipts\Pages;

use App\Filament\Resources\TransferReceipts\TransferReceiptResource;
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
}
