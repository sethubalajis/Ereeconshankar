<?php

namespace App\Filament\Branch\Resources\PromoCodes\Pages;

use App\Filament\Branch\Resources\PromoCodes\PromoCodeResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePromoCode extends CreateRecord
{
    protected static string $resource = PromoCodeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
