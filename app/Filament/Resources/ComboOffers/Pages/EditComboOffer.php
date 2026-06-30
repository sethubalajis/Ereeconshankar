<?php

namespace App\Filament\Resources\ComboOffers\Pages;

use App\Filament\Resources\ComboOffers\ComboOfferResource;
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
}
