<?php

namespace App\Filament\Branch\Resources\ServiceAssignments\Pages;

use App\Filament\Branch\Resources\ServiceAssignments\ServiceAssignmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListServiceAssignments extends ListRecords
{
    protected static string $resource = ServiceAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
