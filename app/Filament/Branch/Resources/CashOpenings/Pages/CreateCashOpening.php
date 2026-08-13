<?php

namespace App\Filament\Branch\Resources\CashOpenings\Pages;

use App\Filament\Branch\Resources\CashOpenings\CashOpeningResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCashOpening extends CreateRecord
{
    protected static string $resource = CashOpeningResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
