<?php

namespace App\Filament\Branch\Resources\TechnicianVisits\Pages;

use App\Filament\Branch\Resources\TechnicianVisits\TechnicianVisitResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTechnicianVisit extends CreateRecord
{
    protected static string $resource = TechnicianVisitResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
