<?php

namespace App\Filament\Branch\Resources\VendorPerformances\Pages;

use App\Filament\Branch\Resources\VendorPerformances\VendorPerformanceResource;
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

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
