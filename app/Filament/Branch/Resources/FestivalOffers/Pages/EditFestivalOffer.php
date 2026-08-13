<?php

namespace App\Filament\Branch\Resources\FestivalOffers\Pages;

use App\Filament\Branch\Resources\FestivalOffers\FestivalOfferResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFestivalOffer extends EditRecord
{
    protected static string $resource = FestivalOfferResource::class;

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
