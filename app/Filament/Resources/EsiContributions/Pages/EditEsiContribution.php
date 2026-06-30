<?php

namespace App\Filament\Resources\EsiContributions\Pages;

use App\Filament\Resources\EsiContributions\EsiContributionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEsiContribution extends EditRecord
{
    protected static string $resource = EsiContributionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
