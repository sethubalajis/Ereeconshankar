<?php

namespace App\Filament\Resources\MerchandisePlannings\Pages;

use App\Filament\Resources\MerchandisePlannings\MerchandisePlanningResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMerchandisePlanning extends CreateRecord
{
    protected static string $resource = MerchandisePlanningResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
