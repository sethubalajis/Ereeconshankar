<?php

namespace App\Filament\Resources\DocumentNumberConfigurations\Pages;

use App\Filament\Resources\DocumentNumberConfigurations\DocumentNumberConfigurationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDocumentNumberConfigurations extends ListRecords
{
    protected static string $resource = DocumentNumberConfigurationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
