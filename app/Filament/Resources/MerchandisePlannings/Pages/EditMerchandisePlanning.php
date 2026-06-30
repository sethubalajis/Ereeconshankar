<?php

namespace App\Filament\Resources\MerchandisePlannings\Pages;

use App\Filament\Resources\MerchandisePlannings\MerchandisePlanningResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMerchandisePlanning extends EditRecord
{
    protected static string $resource = MerchandisePlanningResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
