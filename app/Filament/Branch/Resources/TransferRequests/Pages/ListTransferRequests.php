<?php

namespace App\Filament\Branch\Resources\TransferRequests\Pages;

use App\Filament\Branch\Resources\TransferRequests\TransferRequestResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTransferRequests extends ListRecords
{
    protected static string $resource = TransferRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
