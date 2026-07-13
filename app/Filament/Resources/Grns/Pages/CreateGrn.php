<?php

namespace App\Filament\Resources\Grns\Pages;

use App\Filament\Resources\Grns\GrnResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGrn extends CreateRecord
{
    protected static string $resource = GrnResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
