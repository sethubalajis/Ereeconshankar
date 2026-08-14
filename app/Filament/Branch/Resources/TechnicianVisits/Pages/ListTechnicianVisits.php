<?php

namespace App\Filament\Branch\Resources\TechnicianVisits\Pages;

use App\Filament\Branch\Resources\TechnicianVisits\TechnicianVisitResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTechnicianVisits extends ListRecords
{
    protected static string $resource = TechnicianVisitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
