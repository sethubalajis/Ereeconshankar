<?php

namespace App\Filament\Resources\TechnicianVisits\Pages;

use App\Filament\Resources\TechnicianVisits\TechnicianVisitResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTechnicianVisit extends EditRecord
{
    protected static string $resource = TechnicianVisitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
