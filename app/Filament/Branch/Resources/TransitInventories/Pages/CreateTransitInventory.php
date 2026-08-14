<?php

namespace App\Filament\Branch\Resources\TransitInventories\Pages;

use App\Filament\Branch\Resources\TransitInventories\TransitInventoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTransitInventory extends CreateRecord
{
    protected static string $resource = TransitInventoryResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
