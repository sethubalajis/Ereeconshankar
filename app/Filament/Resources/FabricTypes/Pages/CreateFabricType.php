<?php

namespace App\Filament\Resources\FabricTypes\Pages;

use App\Filament\Resources\FabricTypes\FabricTypeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFabricType extends CreateRecord
{
    protected static string $resource = FabricTypeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
