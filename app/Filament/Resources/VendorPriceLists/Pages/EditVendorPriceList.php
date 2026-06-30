<?php

namespace App\Filament\Resources\VendorPriceLists\Pages;

use App\Filament\Resources\VendorPriceLists\VendorPriceListResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVendorPriceList extends EditRecord
{
    protected static string $resource = VendorPriceListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
