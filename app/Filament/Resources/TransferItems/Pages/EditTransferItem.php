<?php

namespace App\Filament\Resources\TransferItems\Pages;

use App\Filament\Resources\TransferItems\TransferItemResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTransferItem extends EditRecord
{
    protected static string $resource = TransferItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
