<?php

namespace App\Filament\Branch\Resources\VendorPriceLists\Pages;

use App\Filament\Branch\Resources\VendorPriceLists\VendorPriceListResource;
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

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
