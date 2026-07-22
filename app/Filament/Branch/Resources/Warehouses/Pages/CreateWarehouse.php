<?php

namespace App\Filament\Branch\Resources\Warehouses\Pages;

use App\Filament\Branch\Resources\Warehouses\WarehouseResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWarehouse extends CreateRecord
{
    protected static string $resource = WarehouseResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
