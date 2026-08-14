<?php

namespace App\Filament\Branch\Resources\TechnicianVisits\Pages;

use App\Filament\Branch\Resources\TechnicianVisits\TechnicianVisitResource;
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

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
