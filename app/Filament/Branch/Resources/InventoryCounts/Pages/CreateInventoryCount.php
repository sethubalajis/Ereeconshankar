<?php

namespace App\Filament\Branch\Resources\InventoryCounts\Pages;

use App\Filament\Branch\Resources\InventoryCounts\InventoryCountResource;
use Filament\Resources\Pages\CreateRecord;

class CreateInventoryCount extends CreateRecord
{
    protected static string $resource = InventoryCountResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
