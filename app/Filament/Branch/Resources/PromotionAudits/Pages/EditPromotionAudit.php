<?php

namespace App\Filament\Branch\Resources\PromotionAudits\Pages;

use App\Filament\Branch\Resources\PromotionAudits\PromotionAuditResource;
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
