<?php

namespace App\Filament\Resources\ContraEntries\Pages;

use App\Filament\Resources\ContraEntries\ContraEntryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateContraEntry extends CreateRecord
{
    protected static string $resource = ContraEntryResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
