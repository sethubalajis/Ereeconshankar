<?php

namespace App\Filament\Resources\TaxGroups\Pages;

use App\Filament\Resources\TaxGroups\TaxGroupResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTaxGroup extends CreateRecord
{
    protected static string $resource = TaxGroupResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
