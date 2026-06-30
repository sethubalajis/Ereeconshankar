<?php

namespace App\Filament\Resources\PromotionTypes\Pages;

use App\Filament\Resources\PromotionTypes\PromotionTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPromotionType extends EditRecord
{
    protected static string $resource = PromotionTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
