<?php

namespace App\Filament\Resources\EsiContributions\Pages;

use App\Filament\Resources\EsiContributions\EsiContributionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEsiContribution extends CreateRecord
{
    protected static string $resource = EsiContributionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
