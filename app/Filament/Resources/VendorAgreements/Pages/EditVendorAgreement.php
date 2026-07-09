<?php

namespace App\Filament\Resources\VendorAgreements\Pages;

use App\Filament\Resources\VendorAgreements\VendorAgreementResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVendorAgreement extends EditRecord
{
    protected static string $resource = VendorAgreementResource::class;

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
