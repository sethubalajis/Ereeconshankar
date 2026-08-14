<?php

namespace App\Filament\Branch\Resources\ServiceRequests\Pages;

use App\Filament\Branch\Resources\ServiceRequests\ServiceRequestResource;
use Filament\Resources\Pages\CreateRecord;

class CreateServiceRequest extends CreateRecord
{
    protected static string $resource = ServiceRequestResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
