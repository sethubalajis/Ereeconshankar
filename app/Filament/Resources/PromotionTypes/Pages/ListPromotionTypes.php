<?php

namespace App\Filament\Resources\PromotionTypes\Pages;

use App\Filament\Resources\PromotionTypes\PromotionTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPromotionTypes extends ListRecords
{
    protected static string $resource = PromotionTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
