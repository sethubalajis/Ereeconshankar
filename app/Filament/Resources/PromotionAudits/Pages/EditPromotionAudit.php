<?php

namespace App\Filament\Resources\PromotionAudits\Pages;

use App\Filament\Resources\PromotionAudits\PromotionAuditResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPromotionAudit extends EditRecord
{
    protected static string $resource = PromotionAuditResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
