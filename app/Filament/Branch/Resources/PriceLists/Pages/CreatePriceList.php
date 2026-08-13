<?php

namespace App\Filament\Branch\Resources\PriceLists\Pages;

use App\Filament\Branch\Resources\PriceLists\PriceListResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePriceList extends CreateRecord
{
    protected static string $resource = PriceListResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
