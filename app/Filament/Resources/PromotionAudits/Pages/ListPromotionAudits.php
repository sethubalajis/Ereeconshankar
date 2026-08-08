<?php

namespace App\Filament\Resources\PromotionAudits\Pages;

use App\Filament\Resources\PromotionAudits\PromotionAuditResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPromotionAudits extends ListRecords
{
    protected static string $resource = PromotionAuditResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
