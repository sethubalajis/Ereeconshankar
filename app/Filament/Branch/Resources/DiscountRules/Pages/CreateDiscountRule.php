<?php

namespace App\Filament\Branch\Resources\DiscountRules\Pages;

use App\Filament\Branch\Resources\DiscountRules\DiscountRuleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDiscountRule extends CreateRecord
{
    protected static string $resource = DiscountRuleResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
