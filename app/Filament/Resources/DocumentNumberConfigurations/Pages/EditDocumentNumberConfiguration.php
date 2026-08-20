<?php

namespace App\Filament\Resources\DocumentNumberConfigurations\Pages;

use App\Filament\Resources\DocumentNumberConfigurations\DocumentNumberConfigurationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDocumentNumberConfiguration extends EditRecord
{
    protected static string $resource = DocumentNumberConfigurationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
