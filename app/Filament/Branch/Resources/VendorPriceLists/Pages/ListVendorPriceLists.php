<?php

namespace App\Filament\Branch\Resources\VendorPriceLists\Pages;

use App\Filament\Branch\Resources\VendorPriceLists\VendorPriceListResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVendorPriceLists extends ListRecords
{
    protected static string $resource = VendorPriceListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
