<?php

namespace App\Filament\Branch\Resources\VendorPayments\Pages;

use App\Filament\Branch\Resources\VendorPayments\VendorPaymentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVendorPayments extends ListRecords
{
    protected static string $resource = VendorPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
