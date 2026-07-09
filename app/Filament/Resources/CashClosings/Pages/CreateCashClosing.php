<?php

namespace App\Filament\Resources\CashClosings\Pages;

use App\Filament\Resources\CashClosings\CashClosingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCashClosing extends CreateRecord
{
    protected static string $resource = CashClosingResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
