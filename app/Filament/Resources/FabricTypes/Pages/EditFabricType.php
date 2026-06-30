<?php

namespace App\Filament\Resources\FabricTypes\Pages;

use App\Filament\Resources\FabricTypes\FabricTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFabricType extends EditRecord
{
    protected static string $resource = FabricTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
