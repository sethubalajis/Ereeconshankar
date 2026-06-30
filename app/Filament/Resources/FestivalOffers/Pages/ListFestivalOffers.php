<?php

namespace App\Filament\Resources\FestivalOffers\Pages;

use App\Filament\Resources\FestivalOffers\FestivalOfferResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFestivalOffers extends ListRecords
{
    protected static string $resource = FestivalOfferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
