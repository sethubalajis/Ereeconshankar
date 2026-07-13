<?php

namespace App\Filament\Resources\PackingLists\Pages;

use App\Filament\Resources\PackingLists\PackingListResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePackingList extends CreateRecord
{
    protected static string $resource = PackingListResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
