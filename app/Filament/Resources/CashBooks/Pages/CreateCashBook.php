<?php

namespace App\Filament\Resources\CashBooks\Pages;

use App\Filament\Resources\CashBooks\CashBookResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCashBook extends CreateRecord
{
    protected static string $resource = CashBookResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
