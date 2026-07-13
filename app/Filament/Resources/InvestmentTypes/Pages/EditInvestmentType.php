<?php

namespace App\Filament\Resources\InvestmentTypes\Pages;

use App\Filament\Resources\InvestmentTypes\InvestmentTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditInvestmentType extends EditRecord
{
    protected static string $resource = InvestmentTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
