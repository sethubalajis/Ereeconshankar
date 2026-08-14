<?php

namespace App\Filament\Branch\Resources\InventoryReconciliations\Pages;

use App\Filament\Branch\Resources\InventoryReconciliations\InventoryReconciliationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateInventoryReconciliation extends CreateRecord
{
    protected static string $resource = InventoryReconciliationResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
