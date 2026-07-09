<?php

namespace App\Filament\Resources\TcsRecords\Pages;

use App\Filament\Resources\TcsRecords\TcsRecordResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTcsRecord extends CreateRecord
{
    protected static string $resource = TcsRecordResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
