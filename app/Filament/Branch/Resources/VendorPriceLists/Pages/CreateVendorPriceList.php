<?php

namespace App\Filament\Branch\Resources\VendorPriceLists\Pages;

use App\Filament\Branch\Resources\VendorPriceLists\VendorPriceListResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVendorPriceList extends CreateRecord
{
    protected static string $resource = VendorPriceListResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
