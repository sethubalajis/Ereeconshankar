<?php

namespace App\Filament\Resources\HsnSacCodes\Pages;

use App\Filament\Resources\HsnSacCodes\HsnSacCodeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateHsnSacCode extends CreateRecord
{
    protected static string $resource = HsnSacCodeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
