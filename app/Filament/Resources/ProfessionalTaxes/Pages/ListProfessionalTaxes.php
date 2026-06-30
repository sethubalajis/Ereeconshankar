<?php

namespace App\Filament\Resources\ProfessionalTaxes\Pages;

use App\Filament\Resources\ProfessionalTaxes\ProfessionalTaxResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProfessionalTaxes extends ListRecords
{
    protected static string $resource = ProfessionalTaxResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
