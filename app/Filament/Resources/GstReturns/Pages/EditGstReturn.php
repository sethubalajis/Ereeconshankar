<?php

namespace App\Filament\Resources\GstReturns\Pages;

use App\Filament\Resources\GstReturns\GstReturnResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGstReturn extends EditRecord
{
    protected static string $resource = GstReturnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
