<?php

namespace App\Filament\Resources\VendorAgreements\Pages;

use App\Filament\Resources\VendorAgreements\VendorAgreementResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVendorAgreements extends ListRecords
{
    protected static string $resource = VendorAgreementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
