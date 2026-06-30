<?php

namespace App\Filament\Resources\ComboOffers\Pages;

use App\Filament\Resources\ComboOffers\ComboOfferResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListComboOffers extends ListRecords
{
    protected static string $resource = ComboOfferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
