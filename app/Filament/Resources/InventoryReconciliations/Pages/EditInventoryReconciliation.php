<?php

namespace App\Filament\Resources\InventoryReconciliations\Pages;

use App\Filament\Resources\InventoryReconciliations\InventoryReconciliationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditInventoryReconciliation extends EditRecord
{
    protected static string $resource = InventoryReconciliationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
