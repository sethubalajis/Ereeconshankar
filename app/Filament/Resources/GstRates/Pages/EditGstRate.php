<?php

namespace App\Filament\Resources\GstRates\Pages;

use App\Filament\Resources\GstRates\GstRateResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGstRate extends EditRecord
{
    protected static string $resource = GstRateResource::class;

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
