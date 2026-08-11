<?php

namespace App\Filament\Resources\PromotionSettings\Pages;

use App\Filament\Resources\PromotionSettings\PromotionSettingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPromotionSettings extends ListRecords
{
    protected static string $resource = PromotionSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
