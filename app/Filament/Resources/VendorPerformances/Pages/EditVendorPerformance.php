<?php

namespace App\Filament\Resources\VendorPerformances\Pages;

use App\Filament\Resources\VendorPerformances\VendorPerformanceResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVendorPerformance extends EditRecord
{
    protected static string $resource = VendorPerformanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
