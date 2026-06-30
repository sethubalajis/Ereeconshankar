<?php

namespace App\Filament\Resources\TdsRecords\Pages;

use App\Filament\Resources\TdsRecords\TdsRecordResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTdsRecords extends ListRecords
{
    protected static string $resource = TdsRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
