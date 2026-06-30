<?php

namespace App\Filament\Resources\TcsRecords\Pages;

use App\Filament\Resources\TcsRecords\TcsRecordResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTcsRecords extends ListRecords
{
    protected static string $resource = TcsRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
