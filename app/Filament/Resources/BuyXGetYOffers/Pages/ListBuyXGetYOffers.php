<?php

namespace App\Filament\Resources\BuyXGetYOffers\Pages;

use App\Filament\Resources\BuyXGetYOffers\BuyXGetYOfferResource;
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
