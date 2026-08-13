<?php

namespace App\Filament\Branch\Resources\BuyXGetYOffers\Pages;

use App\Filament\Branch\Resources\BuyXGetYOffers\BuyXGetYOfferResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBuyXGetYOffer extends CreateRecord
{
    protected static string $resource = BuyXGetYOfferResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
