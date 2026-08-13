<?php

namespace App\Filament\Branch\Resources\BuyXGetYOffers\Pages;

use App\Filament\Branch\Resources\BuyXGetYOffers\BuyXGetYOfferResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBuyXGetYOffers extends ListRecords
{
    protected static string $resource = BuyXGetYOfferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
