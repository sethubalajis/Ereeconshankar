<?php

namespace App\Filament\Resources\VendorPerformances\Pages;

use App\Filament\Resources\VendorPerformances\VendorPerformanceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVendorPerformances extends ListRecords
{
    protected static string $resource = VendorPerformanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
