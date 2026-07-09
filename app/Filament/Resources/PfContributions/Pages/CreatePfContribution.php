<?php

namespace App\Filament\Resources\PfContributions\Pages;

use App\Filament\Resources\PfContributions\PfContributionResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePfContribution extends CreateRecord
{
    protected static string $resource = PfContributionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
