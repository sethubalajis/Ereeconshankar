<?php

namespace App\Filament\Branch\Resources\PromotionTypes\Pages;

use App\Filament\Branch\Resources\PromotionTypes\PromotionTypeResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePromotionType extends CreateRecord
{
    protected static string $resource = PromotionTypeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
