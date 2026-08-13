<?php

namespace App\Filament\Branch\Resources\ComboOffers\Pages;

use App\Filament\Branch\Resources\ComboOffers\ComboOfferResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditComboOffer extends EditRecord
{
    protected static string $resource = ComboOfferResource::class;

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
