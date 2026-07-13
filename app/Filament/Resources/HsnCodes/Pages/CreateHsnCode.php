<?php

namespace App\Filament\Resources\HsnCodes\Pages;

use App\Filament\Resources\HsnCodes\HsnCodeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateHsnCode extends CreateRecord
{
    protected static string $resource = HsnCodeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
