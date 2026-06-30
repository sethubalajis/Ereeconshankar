<?php

namespace App\Filament\Resources\CashOpenings\Pages;

use App\Filament\Resources\CashOpenings\CashOpeningResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCashOpening extends EditRecord
{
    protected static string $resource = CashOpeningResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
