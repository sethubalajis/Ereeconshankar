<?php

namespace App\Filament\Branch\Resources\Rfqs\Pages;

use App\Filament\Branch\Resources\Rfqs\RfqResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRfq extends CreateRecord
{
    protected static string $resource = RfqResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
