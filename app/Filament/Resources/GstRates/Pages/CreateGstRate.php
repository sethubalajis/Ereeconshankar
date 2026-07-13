<?php

namespace App\Filament\Resources\GstRates\Pages;

use App\Filament\Resources\GstRates\GstRateResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGstRate extends CreateRecord
{
    protected static string $resource = GstRateResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
