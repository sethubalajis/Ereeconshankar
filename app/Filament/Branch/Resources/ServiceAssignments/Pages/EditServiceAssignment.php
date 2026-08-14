<?php

namespace App\Filament\Branch\Resources\ServiceAssignments\Pages;

use App\Filament\Branch\Resources\ServiceAssignments\ServiceAssignmentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditServiceAssignment extends EditRecord
{
    protected static string $resource = ServiceAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
