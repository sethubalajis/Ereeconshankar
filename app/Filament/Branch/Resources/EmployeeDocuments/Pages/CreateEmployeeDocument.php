<?php

namespace App\Filament\Branch\Resources\EmployeeDocuments\Pages;

use App\Filament\Branch\Resources\EmployeeDocuments\EmployeeDocumentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployeeDocument extends CreateRecord
{
    protected static string $resource = EmployeeDocumentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
