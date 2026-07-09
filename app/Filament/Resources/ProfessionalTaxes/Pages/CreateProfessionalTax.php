<?php

namespace App\Filament\Resources\ProfessionalTaxes\Pages;

use App\Filament\Resources\ProfessionalTaxes\ProfessionalTaxResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProfessionalTax extends CreateRecord
{
    protected static string $resource = ProfessionalTaxResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
