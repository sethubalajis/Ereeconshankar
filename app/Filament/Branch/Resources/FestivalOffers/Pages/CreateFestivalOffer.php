<?php

namespace App\Filament\Branch\Resources\FestivalOffers\Pages;

use App\Filament\Branch\Resources\FestivalOffers\FestivalOfferResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFestivalOffer extends CreateRecord
{
    protected static string $resource = FestivalOfferResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
