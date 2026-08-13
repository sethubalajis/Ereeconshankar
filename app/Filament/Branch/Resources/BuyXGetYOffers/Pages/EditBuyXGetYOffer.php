<?php

namespace App\Filament\Branch\Resources\BuyXGetYOffers\Pages;

use App\Filament\Branch\Resources\BuyXGetYOffers\BuyXGetYOfferResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBuyXGetYOffer extends EditRecord
{
    protected static string $resource = BuyXGetYOfferResource::class;

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
