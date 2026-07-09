<?php

namespace App\Filament\Resources\TdsRecords\Pages;

use App\Filament\Resources\TdsRecords\TdsRecordResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTdsRecord extends CreateRecord
{
    protected static string $resource = TdsRecordResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
