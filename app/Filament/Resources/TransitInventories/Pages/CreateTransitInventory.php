<?php

namespace App\Filament\Resources\TransitInventories\Pages;

use App\Filament\Resources\TransitInventories\TransitInventoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTransitInventory extends CreateRecord
{
    protected static string $resource = TransitInventoryResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
