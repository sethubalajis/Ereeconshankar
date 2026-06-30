<?php

namespace App\Filament\Resources\TransferReceipts\Pages;

use App\Filament\Resources\TransferReceipts\TransferReceiptResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTransferReceipts extends ListRecords
{
    protected static string $resource = TransferReceiptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
