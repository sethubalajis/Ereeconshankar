<?php

namespace App\Filament\Resources\ProfessionalTaxes\Pages;

use App\Filament\Resources\ProfessionalTaxes\ProfessionalTaxResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProfessionalTax extends EditRecord
{
    protected static string $resource = ProfessionalTaxResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
