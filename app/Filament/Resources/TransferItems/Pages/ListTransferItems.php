<?php

namespace App\Filament\Resources\TransferItems\Pages;

use App\Filament\Resources\TransferItems\TransferItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTransferItems extends ListRecords
{
    protected static string $resource = TransferItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
