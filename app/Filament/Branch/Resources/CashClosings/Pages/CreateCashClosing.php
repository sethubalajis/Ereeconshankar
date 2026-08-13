<?php

namespace App\Filament\Branch\Resources\CashClosings\Pages;

use App\Filament\Branch\Resources\CashClosings\CashClosingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCashClosing extends CreateRecord
{
    protected static string $resource = CashClosingResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
