<?php

namespace App\Filament\Resources\HsnSacCodes\Pages;

use App\Filament\Resources\HsnSacCodes\HsnSacCodeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHsnSacCode extends EditRecord
{
    protected static string $resource = HsnSacCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
