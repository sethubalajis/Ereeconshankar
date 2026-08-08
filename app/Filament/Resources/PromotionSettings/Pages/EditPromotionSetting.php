<?php

namespace App\Filament\Resources\PromotionSettings\Pages;

use App\Filament\Resources\PromotionSettings\PromotionSettingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPromotionSetting extends EditRecord
{
    protected static string $resource = PromotionSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
